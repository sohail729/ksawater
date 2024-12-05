<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

    public function cpanelShowLogin(Request $request)
    {
        return view('admin.auth.login');

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns,strict',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->type == 'admin') {
                return redirect()->intended('admin/dashboard');
            }
        }
        $request->session()->flash('alert-danger', 'Incorrect Email/Password!');
        return redirect()->route('admin.cpanelShowLogin');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.cpanelShowLogin');
    }
}
