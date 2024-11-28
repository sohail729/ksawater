<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
class LoginController extends Controller
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

    /**
     * Where to redirect users after login.
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
        // $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        // try {
        //     $subject = "Welcome to Now2Rent - Let's Hit the Road!";
        //     $message = "Welcome to Now2Rent! 🚗 Get ready for smooth rides and unforgettable adventures with us. Start your journey now by exploring our fleet of vehicles and booking your perfect ride.\n\nLet's make every mile memorable!\nNow2Rent Team";

        //     Mail::mailer('no_reply')->raw($message, function($mail) use ($subject){
        //         $mail->to('itssohail97@gmail.com');
        //         $mail->from(env('NO_REPLY_MAIL_FROM_ADDRESS'), env('NO_REPLY_MAIL_FROM_NAME'));
        //         $mail->subject($subject);
        //     });

        // } catch (Exception $e) {
        //     Log::debug('Email sending failed: ' . $e->getMessage());
        // }


        $request->validate([
            'email' => 'required|email:rfc,dns,strict',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
            if(Auth::check()){
                $updateData = [];
                $user = User::find(auth()->user()->id);
                // if(auth()->user()->status == 0){
                //     $request->session()->flash('alert-danger','Your account is not active. Please contact support for assistance.');
                //     Auth::logout();
                //     return redirect()->route('login');
                // }

                if(auth()->user()->is_featured){
                    $featuredEnd = Carbon::parse(auth()->user()->featured_end);
                    $diff = $featuredEnd->diff(now());

                    if (!$diff->invert) {
                        $updateData['is_featured'] = 0;
                        // $user->update(['is_featured' => 0]);
                        $user->featuredCars()->delete();
                    }
                }

                $subscriptionEnd = Carbon::parse(auth()->user()->subscription_end);
                $diff = $subscriptionEnd->diff(now());

                if (!$diff->invert) {
                    if(auth()->user()->status == 1){
                        $updateData['status'] = 2;
                        $updateData['last_verified'] = $user->verified_on;
                        // $user->update(['status' => 2, 'last_verified' => $user->verified_on]);
                        $user->cars()->update(['is_active' => 0]);
                        $request->session()->flash('alert-danger','Your subscription has expired. Please renew to continue accessing our services.');
                    }
                }

                $updateData['last_login'] = now();
                $user->update($updateData);
                return redirect()->intended('dashboard');
            }
        }
        else{
             $request->session()->flash('alert-danger', 'Incorrect Email/Password!');
             return redirect()->route('login');
        }
    }
}
