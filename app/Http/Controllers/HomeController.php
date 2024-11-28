<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\ContactUs;
use App\Models\FeaturedCar;
use App\Models\FeaturedPlan;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $carbrands = [];
        // $cartypes = [];
        $cities = DB::table('cities')->where('status', 1)->orderBy('name')->get();

        if (cache()->has('featured_cars') && count(cache('featured_cars')) >= 8) {
            $featured_cars = cache('featured_cars');
        } else {
            $featured_cars = Car::has('featured')->where('cars.is_active', 1)->with('images')->limit(8)->get();
            cache(['featured_cars' => $featured_cars]);
        }

        if(cache()->has('carbrands') && cache()->has('cartypes')){
            $carbrands = cache('carbrands');
            $cartypes = cache('cartypes');
        }else{
            $carbrands = CarBrand::where('is_top', 1)->get();
            $cartypes = CarType::limit(8)->get();
            cache(['carbrands' => $carbrands, 'cartypes' => $cartypes]);
        }

        $ad1 = Advertisement::select('title', 'status', 'position', 'image')->where('position', 'h1')->where('status', 1)->latest()->first();
        $ad2 = Advertisement::select('title', 'status', 'position', 'image')->where('position', 'h2')->where('status', 1)->latest()->first();

        // dd($carbrands);
        return view('index', compact('cities', 'featured_cars', 'cartypes', 'carbrands', 'ad1', 'ad2'));
    }

    public function getCarDetail(Request $request)
    {
        // dd($request->all());
        $arr = explode('-', $request->slug);
        $id = end($arr);
        $car = Car::find($id);
        if(empty($car)){
            abort(404, 'Car not found!');
        }
        return view('detailpage', compact('car'));
    }

    public function getMoreCarTypes(Request $request)
    {
        $cartypes = CarType::skip(8)->take(PHP_INT_MAX)->get();
        return response()->json($cartypes);
    }

    public function showPackagesPage(Request $request)
    {
        $subscription_plans = cache('subscription_plans');
        $featured_plans = [];
        foreach ($subscription_plans as &$plan) {
            if(!empty($plan['featured_duration'])){
                $featured_plans = $plan['featured'];
            }
            unset($plan['featured_duration']);
            unset($plan['featured']);
        }

        return view('dashboard.packages', compact('subscription_plans', 'featured_plans'));
    }

    public function aboutus(Request $request)
    {
        return view('aboutus');
    }
    public function terms(Request $request)
    {
        return view('terms');
    }
    public function copyright(Request $request)
    {
        return view('copyright');
    }
    public function privacy(Request $request)
    {
        return view('privacy');
    }
    public function contact(Request $request)
    {
        return view('contactus');
    }

    public function changeLocale(Request $request)
    {
        session(['lang' => $request->lang]);
        return response()->json(['status' => 200, 'url' => $request->server('HTTP_REFERER')]);
    }

    public function contactSubmit(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        if($exists){
            $request->request->add(['is_user' => 1]);
        }
        ContactUs::create($request->all());
        return redirect()->back()->with('alert-success', 'Thank you for reaching out! Your message has been successfully received. We will get back to you shortly.');
    }
}
