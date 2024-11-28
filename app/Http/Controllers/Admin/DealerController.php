<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DealerController extends Controller
{
    public function index(Request $request)
    {
        $dealers = User::where('type', 'dealer')->get();
        return view('admin.dealer.index', compact('dealers'));

    }

    public function show(Request $request)
    {
        $packages = cache('subscription_plans');
        foreach ($packages as &$plan) {
            unset($plan['featured_duration']);
            unset($plan['featured']);
        }
        $dealer = User::with('cars')->whereId($request->dealer_id)->first();
        return view('admin.dealer.show', compact('dealer', 'packages'));

    }

    public function giveawaySubscription(Request $request)
    {
        $duration = $request->duration;
        $range = $request->range;
        $paid_amount = $request->amount;
        $dealer_id = $request->dealer_id;
        $uploadLimit = str_replace("upto", "", $range);
        $updateData = [];
        $featuredData = [];
        $dealer = User::find($dealer_id);
        $packages = json_decode(json_encode(cache('subscription_plans')));
        if ($packages->{$duration}->featured_duration != 0) {
            $featured_duration = $packages->{$duration}->featured_duration;
            $featured_start = Carbon::now()->toDateTimeString();
            $featured_end = Carbon::now()->addDays($featured_duration)->toDateTimeString();

            $featuredData = [
                'is_featured' => $packages->{$duration}->featured_duration != 0 ? 1 : 0,
                'featured_limit' => $featured_duration,
                'featured_start' => $featured_start,
                'featured_end' => $featured_end,
            ];

        }

        $updateData = array_merge($updateData, $featuredData);
        $subscription_end = Carbon::now()->addMonths($duration)->toDateTimeString();

        $paymentData = [
            'status' => 1,
            'package' => $duration.'/'.$range,
            'upload_limit' => $uploadLimit,
            'is_verified' => 1,
            'payment_status' => 'paid',
            'subscription_end' => $subscription_end,
        ];

        $updateData = array_merge($updateData, $paymentData);
        $dealer->update($updateData);

        Subscription::create([
            'status' => 'giveaway',
            'paid_amount' => $paid_amount,
            'package' => $duration.'/'.$range,
            'upload_limit' => $uploadLimit,
            'subscription_end' => $subscription_end,
            'dealer_id' => $dealer->id
        ]);
        return response()->json(['status' => 200, 'message' => 'Package Giveaway Successful!']);
    }

    public function block($id, Request $request)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->block_reason = $request->reason ?? "Blocked User";
        $user->save();
        $user->cars()->update(['is_active' => 0]);
        return response()->json(['status' => 200, 'message' => 'Dealer has been blocked.']);
    }

    public function unblock($id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->block_reason =  "";
        $user->save();
        $user->cars()->update(['is_active' => 1]);
        return response()->json(['status' => 200, 'message' => 'Dealer has been unblocked.']);
    }

    public function updateDetail(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required_with:password|same:password',
            'image' => 'image|mimes:jpeg,png,jpg',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $user = User::find(auth()->user()->id);

        if(!empty($request->password)){
            $user->password = $request->password;
        }

        $userDetail = UserDetail::where('user_id', $user->id)->first();
        if(!empty($request->image)){
            $userDetail->image = self::uploadToS3($request);
        }
        $userDetail->phone = $request->phone;
        $userDetail->address = $request->address;
        $userDetail->city = $request->city;
        $userDetail->country = $request->country;

        $user->save();
        $user->detail()->save($userDetail);


        if(!empty($request->password)){
            auth()->logout();
            return redirect()->route('login')->with('alert-danger', 'You have been logged out.');
        }

        return redirect()->back()->with('alert-success', 'Profile Updated!');;


    }

    protected static function uploadToS3($request){
        $image = $request->file('image'); // Get the uploaded file
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        Storage::disk('s3')->put('images/' . $imageName, file_get_contents($image), 'public');
        return Storage::disk('s3')->url('images/' . $imageName);
    }
}
