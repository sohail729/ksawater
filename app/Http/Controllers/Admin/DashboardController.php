<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\ContactUs;
use App\Models\User;
use App\Models\WebsiteVisit;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;

class DashboardController extends Controller
{
    public function index()
    {

        return view('admin.dashboard');

        // $supportMsgs = ContactUs::limit(4)->orderByDesc('created_at')->get();
        // $activeSubs = User::where(['type' => 'dealer' , 'status' => 1])->count();
        // $expiredSubs = User::where(['type' => 'dealer' , 'status' => 2])->count();
        // $listedCars = Car::where('is_active', 1)->count();
        // $onRentCars = Car::where(['is_active' => 1, 'status' => 2])->count();
        // $total_vists = WebsiteVisit::count();
        // $unique_visits = WebsiteVisit::distinct('ip_address')->count('ip_address');
        // $payments = PaymentIntent::all();
        // $totalEarnings = 0;
        // foreach ($payments->data as $payment) {
        //     if ($payment->status === 'succeeded') {
        //         $totalEarnings += $payment->amount_received;
        //     }
        // }
        // $totalEarnings = $totalEarnings / 100;
        // return view('admin.dashboard', compact('supportMsgs', 'totalEarnings', 'activeSubs', 'expiredSubs', 'listedCars', 'onRentCars', 'total_vists', 'unique_visits'));
    }
}
