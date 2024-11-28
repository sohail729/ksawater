<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('dealer')->where('dealer_id', $request->dealer_id)->orderBy('updated_at', 'desc')->get();

        // dd( $subscriptions);
        $dealer = User::find($request->dealer_id);
        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);
        $packages = cache('subscription_plans');
        return view('admin.dealer.subscription', compact('subscriptions', 'dealer', 'packages'));

    }

    public function generatePayment(Request $request)
    {

        $packages = cache('subscription_plans');
        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);

        $duration = $request->package['duration'];
        $range = $request->package['range'];
        $uploadLimit = str_replace("upto", "", $range);

        $amount = $packages[$duration][$range];
        $amount = number_format(($amount + ($amount * 0.21)), 2) * 100;

        $packageName = "Package: Duration $duration Months with upto ". str_replace('upto', '', $range) ." Upload Limit";
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
                'success_url' => route('payment.callback', ['userID' => $request->dealer_id]),
                'cancel_url' => route('payment.callback', ['userID' => $request->dealer_id]),
            ]);

            User::find($request->dealer_id)->update([
                'payment_session' => $session->id
            ]);

            $subs = Subscription::create([
                'status' => 'unpaid',
                'package' => implode('/', array_values($request->package)),
                'upload_limit' => $uploadLimit,
                'payment_link' => $session->url,
                'dealer_id' => $request->dealer_id
            ]);

            // $user->update(['payment_link' => $session->url]);
            return $session->url;


        // $subscriptions = Subscription::with('dealer')->where('dealer_id', $request->dealer_id)->get();
        // $dealer = User::find($request->dealer_id);
        // $packages = json_decode(file_get_contents(public_path('packages.json')), true);
        // return view('admin.dealer.subscription', compact('subscriptions', 'dealer', 'packages'));

    }
}
