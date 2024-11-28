<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\FeaturedPlan;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function getSubscriptionPlans(Request $request)
    {
        $plans = SubscriptionPlan::orderBy('period')->get();
        $featured_plans = FeaturedPlan::orderBy('duration')->get();
        return view('admin.subscription-plans', compact('plans', 'featured_plans'));
    }

    public function addOrUpdatePlan(Request $request)
    {
        $msg = '';
        if($request->type == 'featured'){
            if(empty($request->id)){
                FeaturedPlan::create([
                    'duration' => $request->duration,
                    'limit' => 'upto'.$request->limit,
                    'price' => $request->price
                ]);
                $msg = 'New Featured Plan Created!';
            }else{
                FeaturedPlan::find($request->id)->update([
                    'duration' => $request->duration,
                    'limit' => 'upto'.$request->limit,
                    'price' => $request->price
                ]);
                $msg = 'Featured Plan Updated!';
            }
        }

        if($request->type == 'basic'){
            $packages = [];
            foreach ($request->limit as $key => $limit) {
                $index = 'upto'.$limit;
                $packages[$index] =  $request->price[$key];
            }
            $packages = json_encode($packages);

            if(empty($request->id)){
                SubscriptionPlan::create([
                    'is_featured' => !empty($request->is_featured) ? 1 : 0,
                    'featured_duration' => !empty($request->featured_duration) ? $request->featured_duration : 0,
                    'period' => $request->period,
                    'packages' => $packages
                ]);
                $msg = 'New Basic Plan Created!';
            }else{
                SubscriptionPlan::find($request->id)->update([
                    'is_featured' => !empty($request->is_featured) ? 1 : 0,
                    'featured_duration' => !empty($request->featured_duration) ? $request->featured_duration : 0,
                    'period' => $request->period,
                    'packages' => $packages
                ]);
                $msg = 'Basic Plan Updated!';
            }
        }

        // Update Cache

        $featuredPlans = FeaturedPlan::orderBy('duration')->get()->groupBy('duration');
        $basicPlans = SubscriptionPlan::all()->groupBy('period');
        $subscription_plans = [];
        $featured = [];
        foreach ($featuredPlans as $key => $plan) {
            $featured[$key] = $plan->pluck('price', 'limit')->toArray();
        }
        foreach ($basicPlans as $key => $plan) {
            $plan = $plan[0];
            $subscription_plans[$key] = json_decode($plan->packages, true);
            $subscription_plans[$key]['featured_duration'] = $plan->featured_duration;
            $subscription_plans[$key]['featured'] = $featured;
        }
        Cache::put('subscription_plans' , $subscription_plans);

        return redirect()->back()->with('alert-success', $msg);
    }

    public function deletePlan(Request $request)
    {
        if($request->type == 'featured'){
            FeaturedPlan::find($request->id)->delete();
        }

        if($request->type == 'basic'){
            SubscriptionPlan::find($request->id)->delete();
        }


        // Update Cache

        $featuredPlans = FeaturedPlan::orderBy('duration')->get()->groupBy('duration');
        $basicPlans = SubscriptionPlan::all()->groupBy('period');
        $subscription_plans = [];
        $featured = [];
        foreach ($featuredPlans as $key => $plan) {
            $featured[$key] = $plan->pluck('price', 'limit')->toArray();
        }
        foreach ($basicPlans as $key => $plan) {
            $plan = $plan[0];
            $subscription_plans[$key] = json_decode($plan->packages, true);
            $subscription_plans[$key]['featured_duration'] = $plan->featured_duration;
            $subscription_plans[$key]['featured'] = $featured;
        }
        Cache::put('subscription_plans' , $subscription_plans);

        return true;
    }

    public function showSubscriptionPlanForm(Request $request)
    {
        return view('admin.subscription-plans-form');
    }

    public function getCities()
    {
        $cities = DB::table('cities')->orderBy('name')->get();
        return view('admin.cities', compact('cities'));
    }

    public function updateCity(Request $request)
    {
        DB::table('cities')->whereId($request->city_id)->update([
            'name' =>  $request->cityName ?? "",
            'status' =>  $request->cityStatus ?? 0
        ]);
        return redirect()->back()->with('alert-success', 'City info updated!');;
    }
    public function showQueriesList(Request $request)
    {
        $queries = ContactUs::orderByDesc('created_at')->get();
        return view('admin.contact-queries', compact('queries'));
    }
}
