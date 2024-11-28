<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Stripe\Checkout\Session;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
class UserDetailController extends Controller
{
    public function getProfile(Request $request)
    {
        $cities = DB::table('cities')->where('status', 1)->orderBy('name')->get();
        return view('dashboard.profile-edit', compact('cities'));
    }

    public function updateDetail(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'password_confirmation' => 'required_with:password|same:password',
            // 'image' => 'image|mimes:jpeg,png,jpg',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
        ]);

        $user = User::find(auth()->user()->id);

        if(!empty($request->password)){
            $user->password = $request->password;
            $user->save();
        }

        $userDetail = UserDetail::where('user_id', $user->id)->first();
        if(!empty($request->image)){
            $userDetail->image = self::uploadToS3($request);
        }
        $userDetail->phone = $request->phone;
        $userDetail->address = $request->address;
        $userDetail->city = $request->city;
        $userDetail->postcode = $request->postcode;

        $user->detail()->save($userDetail);

        if(!empty($request->password)){
            auth()->logout();
            return redirect()->route('login')->with('alert-danger', 'You have been logged out.');
        }

        return redirect()->back()->with('alert-success', 'Profile Updated!');;


    }

    public function getSubscriptions(Request $request){

        $dealer = auth()->user()->load(['subscriptions']);

        return view('dashboard.subscriptions', compact('dealer'));
    }

    protected static function uploadToS3($request){
        $image = $request->file('image'); // Get the uploaded file
        $thumbnail = Image::make($image)->fit(150)->encode('jpg', 50);
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        Storage::disk('s3')->put('images/' . $imageName, $thumbnail, 'public');
        return Storage::disk('s3')->url('images/' . $imageName);
    }

    public function renewFeatured(Request $request)
    {
        $user = User::where('username', $request->d)->first();

        if(empty($user)){
            return 400;
        }
        $selected_package = explode('/', $request->p);

        $packages = cache('subscription_plans');
        $featured_packages = Arr::pluck($packages, 'featured');
        $featured_packages = Arr::first($featured_packages);
        $featured_packages = collect($featured_packages);
        $featured_packages = $featured_packages->has($selected_package[0]) ? $featured_packages->get($selected_package[0]) : null;
        if(!empty($featured_packages)){

            $amount = $featured_packages[$selected_package[1]];
            $amount = number_format($amount, 2) * 100;
            $duration = $selected_package[0];
            $limit = str_replace("upto", "", $selected_package[1]);
            $packageName = "Featured Package: Duration $duration Days with upto ". $limit ." Upload Limit";

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'EUR',
                            'product_data' => [
                                'name' => $packageName,
                            ],
                            'unit_amount' => $amount
                        ],
                        'quantity' => 1,
                    ],
                ],
                'payment_intent_data' => [
                    'description' => 'Featured Subscription',
                ],
                'metadata' => [
                    'dealer_username' => $user->username,
                    'dealer_email' => $user->email,
                    'featured_duration' => $duration,
                    'featured_limit' => $limit
                ],
                'mode' => 'payment',
                'success_url' => route('featured-payment.callback', ['userID' => $user->id]),
                'cancel_url' => route('featured-payment.callback', ['userID' => $user->id]),
            ]);
            $user->update(['featured_session' => $session->id, 'featured_link' => $session->url]);
            return redirect($session->url);
        }
    }

    public function renewPackage(Request $request)
    {
        $user = User::where('username', $request->d)->first();

        if(empty($user)){
            return 400;
        }

        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);
        $packages = cache('subscription_plans');

        $package = explode('/', $request->p);
        $duration = $package[0];
        $range = $package[1];
        $limit = str_replace("upto", "", $range);

        $amount = $packages[$duration][$range];
        $packageAmount = $amount;
        $amount = number_format(($amount + ($amount * 0.21)), 2) * 100;

        $packageName = "Package: Duration $duration Months with upto ". $limit ." Upload Limit";

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $packageName,
                        ],
                        'unit_amount' => $amount
                    ],
                    'quantity' => 1,
                ],
            ],
            'payment_intent_data' => [
                'description' => 'Subscription Renewal',
            ],
            'metadata' => [
                'dealer_username' => $user->username,
                'dealer_email' => $user->email,
                'duration' => $duration,
                'limit' => $limit,
                'package_amount' => $packageAmount
            ],
            'mode' => 'payment',
            'success_url' => route('payment.callback', ['userID' => $user->id]),
            'cancel_url' => route('payment.callback', ['userID' => $user->id]),
        ]);

        // try {
        //     $subject = "Welcome Back to Now2Rent!";
        //     $message = "Dear [User],

        //     We're delighted to welcome you back to Now2Rent! Your trust means everything to us, and we're here to make your car rental experience exceptional.

        //     Resume your journey with us by exploring our latest selection of vehicles. Whether you're renting for work or play, we're here to provide you with the perfect ride.

        //     Thank you for choosing Now2Rent again. Let's make every mile memorable!

        //     Happy travels,
        //     The Now2Rent Team";

        //     Mail::mailer('no_reply')->raw($message, function($mail) use ($subject, $user){
        //         $mail->to($user->email, $user->fullname);
        //         $mail->from(env('NO_REPLY_MAIL_FROM_ADDRESS'), env('NO_REPLY_MAIL_FROM_NAME'));
        //         $mail->subject($subject);
        //     });

        // } catch (Exception $e) {
        //     Log::debug('Email sending failed: ' . $e->getMessage());
        // }


        $user->update(['package' => $request->p, 'payment_session' => $session->id, 'payment_link' => $session->url]);
        return redirect($session->url);
    }
}
