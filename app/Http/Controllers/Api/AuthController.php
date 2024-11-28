<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Stripe\Checkout\Session;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Stripe\PaymentIntent;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    // use RegistersUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register', 'paymentCallback', 'getDealerSubscriptions']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if(!$request->has('email') || !$request->has('password')){
            return $this->responseJson(400, 'Email or password field missing!');
        }

        if (!$token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->responseJson(400, 'Invalid Credentials');
        }

        $user = auth('api')->user();
        $userData = new stdClass;
        $userData->id = $user->id;
        $userData->status = $user->status;
        $userData->block_reason = $user->block_reason;
        $userData->image = $user->detail->image;
        $userData->phone = $user->detail->phone;
        $userData->email = $user->email;
        $userData->postcode = $user->detail->postcode;
        $userData->address = $user->detail->address;
        $userData->city = $user->detail->city;
        $userData->username = $user->username;
        $userData->fullname = $user->fullname;
        $userData->package = $user->package;
        $userData->upload_limit = $user->upload_limit;
        $userData->payment_status = $user->payment_status;
        $userData->payment_date = $user->payment_date;
        $userData->subscription_end = $user->subscription_end;
        $userData->is_verified = $user->is_verified;
        $userData->verified_on = $user->verified_on;
        $userData->is_featured = $user->is_featured;
        $userData->featured_limit = $user->featured_limit;
        $userData->featured_start = $user->featured_start;
        $userData->featured_end = $user->featured_end;
        $userData->registered_on = $user->created_at->format('Y-m-d H:i:s');

        $data = [
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL()
            ],
            'user' => $userData
        ];

        if(auth('api')){
            return $this->responseJson(200, 'Logged in successfully', $data);
        }
        return $this->responseJson(400, 'Something went wrong!');
    }

    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            $responseData = [
                'dealer_id' => $user->id,
                'payment_status' => $user->payment_status,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'payment_intent' => $user->payment_intent,
                'payment_session' => $user->payment_session,
                'payment_link' =>  $user->payment_link
            ];
            return $this->responseJson(422, 'Dealer Already Exists!', $responseData);
        }

        try {
            $validatedData = $request->validate([
                'username' => 'required|string|unique:users',
                'fullname' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => 'required|string',
                'address' => 'required|string',
                'postcode' => 'required|string',
                'city' => 'required|string',
                // 'duration' => 'required|string',
                // 'range' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return $this->responseJson(422, 'Invalid Parameters!', null,  $e->validator->errors());
        }

        // $request->merge([
        //     'package' => [
        //         'duration' => $request->duration,
        //         'range' => $request->range
        //     ]
        // ]);

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

        // $packages = cache('subscription_plans');
        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);

        // $duration = $request->package['duration'];
        // $range = $request->package['range'];

        // $amount = $packages[$duration][$range];
        // $amount = number_format(($amount + ($amount * 0.21)), 2) * 100;

        // $packageName = "Package: Duration $duration Months with upto ". str_replace('upto', '', $range) ." Upload Limit";
        //     $session = Session::create([
        //         'payment_method_types' => ['card'],
        //         'line_items' => [
        //             [
        //                 'price_data' => [
        //                     'currency' => 'EUR',
        //                     'product_data' => [
        //                         'name' => $packageName,
        //                     ],
        //                     'unit_amount' => $amount,
        //                 ],
        //                 'quantity' => 1,
        //             ],
        //         ],
        //         'mode' => 'payment',
        //         'success_url' => route('payment.callback', ['userID' => $user->id]),
        //         'cancel_url' => route('payment.callback', ['userID' => $user->id]),
        //     ]);

        //     $user->update(['payment_session' => $session->id, 'payment_link' => $session->url]);

            $responseData = [
                'dealer_id' => $user->id,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'payment_status' => 'unpaid',
                // 'payment_session' => $session->id,
                // 'package_name' => $packageName,
                // 'package_amount' => $amount / 100,
                // 'payment_link' =>  $session->url,
            ];
            return $this->responseJson(200, 'Dealer Registration Successful', $responseData);
    }

    public function paymentCallback(Request $request)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($request->paymentIntent);
            if($paymentIntent->status == 'succeeded'){
                $user = User::find($request->dealerId);

                $updateData = [];
                $featuredData = [];
                $package = explode('/', $user->package);
                $duration = $package[0];
                $uploadLimit = str_replace("upto", "", $package[1]);

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
                    'payment_intent' => $paymentIntent->id,
                    'payment_email' => $paymentIntent->customer_details->email ?? "",
                    'payment_date' => gmdate('Y-m-d H:i:s'),
                    'subscription_end' => $subscription_end,
                    'verified_on' => gmdate('Y-m-d H:i:s')
                ];

                $updateData = array_merge($updateData, $paymentData);
                $user->update($updateData);
                if ($user->cars()->exists()) {
                    $user->cars()->update(['is_active' => 1]);
                }

                $subscription = Subscription::create([
                    'dealer_id' => $user->id,
                    'payment_intent' => $paymentIntent->id,
                    'status' => 'paid',
                    'package' => $user->package,
                    'upload_limit' => $uploadLimit,
                    'payment_date' => gmdate('Y-m-d H:i:s'),
                    'subscription_end' => $subscription_end,
                    'payment_email' => $paymentIntent->customer_details->email ?? ""
                ]);

                return $this->responseJson(200, 'Great! You are now our premium member.', $subscription);
            }else{
                return $this->responseJson(400, 'Payment Unsuccessful. Error code: '.$paymentIntent->status);
            }
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $this->responseJson(400, $e->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        return auth('api')->user();
        // return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function getDealerStatus(Request $request)
    {
        try {
            $request->validate([
                'dealer_id' => 'required',
            ]);
            $user = User::where('id', $request->dealer_id)->first();

            if(empty($user)){
                return $this->responseJson(400, 'Dealer not found.');
            }

            $userData = new stdClass;
            $userData->id = $user->id;
            $userData->status = $user->status;
            $userData->block_reason = $user->block_reason;
            $userData->image = $user->detail->image;
            $userData->phone = $user->detail->phone;
            $userData->email = $user->email;
            $userData->postcode = $user->detail->postcode;
            $userData->address = $user->detail->address;
            $userData->city = $user->detail->city;
            $userData->username = $user->username;
            $userData->fullname = $user->fullname;
            $userData->package = $user->package;
            $userData->upload_limit = $user->upload_limit;
            $userData->payment_status = $user->payment_status;
            $userData->payment_date = $user->payment_date;
            $userData->subscription_end = $user->subscription_end;
            $userData->is_verified = $user->is_verified;
            $userData->verified_on = $user->verified_on;
            $userData->is_featured = $user->is_featured;
            $userData->featured_limit = $user->featured_limit;
            $userData->featured_start = $user->featured_start;
            $userData->featured_end = $user->featured_end;
            $userData->registered_on = $user->created_at->format('Y-m-d H:i:s');

            if($user->status == 0){
                // auth()->logout();
                return $this->responseJson(200, 'Your account is not active. Please contact support for assistance.', ['dealer' => $userData]);
            }

            if($user->is_featured){
                $featuredEnd = Carbon::parse($user->featured_end);
                $diff = $featuredEnd->diff(now());

                if (!$diff->invert) {
                    $user->update(['is_featured' => 0]);
                    $userData->is_featured = $user->is_featured;
                    $user->featuredCars()->delete();
                }
            }

            $subscriptionEnd = Carbon::parse($user->subscription_end);
            $diff = $subscriptionEnd->diff(now());

            if (!$diff->invert) {
                if($user->status == 1){
                    $user->update(['status' => 2, 'payment_status' => 'expired' ,'last_verified' => $user->verified_on]);
                    $userData->status = $user->status;
                    $userData->payment_status = $user->payment_status;
                    $user->cars()->update(['is_active' => 0]);
                }
                return $this->responseJson(200, 'Your subscription has expired. Please renew to continue accessing our services.', ['dealer' => $userData]);
            }

            return $this->responseJson(200, 'Dealer Status', ['dealer' => $userData]);

        } catch (ValidationException $e) {
            return $this->responseJson(422, 'Invalid Parameter', null,  $e->validator->errors());
        }
    }

    public function getDealerSubscriptions(Request $request)
    {
        $request->validate([
            'dealer_id' => 'required',
        ]);
        $data = [];
        $subscriptions = Subscription::where('dealer_id', $request->dealer_id)->orderByDesc('updated_at')->get();

        if(!empty($subscriptions)){
            foreach ($subscriptions as $key => $subscription) {
                $package = explode('/', $subscription->package);
                $data[$key]['id'] = $subscription->id;
                $data[$key]['status'] = $subscription->status;
                $data[$key]['dealer_id'] = $subscription->dealer_id;
                $data[$key]['duration'] = current($package);
                $data[$key]['upload_limit'] = end($package);
                $data[$key]['payment_date'] = $subscription->payment_date ?? "";
                $data[$key]['subscription_end'] = $subscription->subscription_end;
            }
            return $this->responseJson(200, 'Subscriptions History', $data);
        }
        return $this->responseJson(400, 'Subscriptions Not Found!');
    }

    public function createPaymentIntent(Request $request)
    {
        $dealer = User::where('id', $request->dealer_id)->first();
        $request->merge([
            'package' => [
                'duration' => $request->duration,
                'limit' => $request->limit
            ]
        ]);
        $duration = $request->duration;
        $limit = $request->limit;
        $package = implode('/upto', array_values($request->package));

        if(empty($dealer)){
            return $this->responseJson(400, 'Dealer not found.');
        }

        $paymentIntent = PaymentIntent::create([
            'amount' => ($request->amount * 100),
            'currency' => 'EUR',
            'metadata' => [
                'dealer_username' => $dealer->username,
                'dealer_email' => $dealer->email,
                'duration' => $duration,
                'limit' => $limit
            ],
        ]);

        $dealer->update(['package' => $package, 'payment_intent' => $paymentIntent->id]);

        return $this->responseJson(200, 'Payment Intent Created.', [
            'id' => $paymentIntent->id,
            'client_secret' => $paymentIntent->client_secret
        ]);
    }
}
