<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\FeaturedCar;
use App\Models\CarImage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CarController extends Controller
{
    public function updateStatus(Request $request)
    {
        $rules = [
            'dealer_id' => 'required',
            'car_id' => 'required',
            'status' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }

        Car::where(['id' => $request->car_id, 'user_id' => $request->dealer_id])
        ->update(['status' => $request->status]);

        return $this->responseJson(200, 'Car Status Updated!');
    }

    public function addNew(Request $request)
    {
        $rules = [
            'dealer_id' => 'required|string',
            'pickup_location' => 'required|string',
            'type' => 'required|string',
            'brand_id' => 'required|string',
            'model_id' => 'required|string',
            'year' => 'required|string',
            'power_size' => 'required|string',
            'cost_per_day' => 'required|string',
            'cost_per_week' => 'required|string',
            'cost_per_month' => 'required|string',
            'deposit' => 'required|string',
            'mileage' => 'required|string',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'seats' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }

        $carObj = new Car();
        $carObj->user_id = $request->dealer_id;
        if($request->has('delivery_possible')){
            $carObj->delivery_possible = $request->delivery_possible;
        }
        if($request->has('insurance_included')){
            $carObj->insurance_included = $request->insurance_included;
        }
        $carObj->pickup_location = $request->pickup_location;
        $carObj->type = $request->type;
        $carObj->brand_id = $request->brand_id;
        $carObj->model_id = $request->model_id;
        $carObj->year = $request->year;
        $carObj->power_size = $request->power_size;
        $carObj->cost_per_day = $request->cost_per_day;
        $carObj->cost_per_week = $request->cost_per_week;
        $carObj->cost_per_month = $request->cost_per_month;
        $carObj->deposit = $request->deposit;
        $carObj->mileage = $request->mileage;
        $carObj->transmission = $request->transmission;
        $carObj->fuel_type = $request->fuel_type;
        $carObj->seats = $request->seats;
        $carObj->status = 1;
        $carObj->is_active = 1;
        $carObj->save();

        if (!empty($request->is_featured)) {
            $featuredCar = new FeaturedCar;
            $featuredCar->dealer_id = $request->dealer_id;
            $featuredCar->car_id = $carObj->id;
            $featuredCar->save();
        }


        if(!empty($request->images)){
            foreach($request->images as $key => $image){
                CarImage::create([
                    'car_id' => $carObj->id,
                    'thumbnail' =>  $image['thumbnail'],
                    'full' =>  $image['full'],
                ]);
            }
        }

        return $this->responseJson(200, 'Car Listed Successful!', $carObj);
    }

    public function getCarsByDealer(Request $request)
    {
        if(empty($request->dealer_id)){
            return $this->responseJson(400, 'Dealer id is required!');
        }

        $cars = Car::with(['images','dealer' => function ($q) {
            $q->select(['id', 'username', 'fullname', 'is_verified', 'is_featured', 'email']);
        }, 'dealer.detail' => function($q){
            $q->select(['user_id', 'image', 'phone', 'address', 'city']);
        }, 'brand' => function($q){
            $q->select(['id', 'name', 'logo']);
        }, 'model' => function($q){
            $q->select(['id', 'name']);
        }])
        ->where('user_id', $request->dealer_id)
        ->get();

        return $this->responseJson(200, 'Cars Fetched By Dealer ID!', $cars);
    }

    public function getCarDetail(Request $request)
    {
        if(empty($request->car_id)){
            return $this->responseJson(400, 'Car Not Found!');
        }
        $car = Car::with(['dealer' => function ($q) {
            $q->select(['id', 'username', 'fullname', 'is_verified', 'is_featured', 'email']);
        }, 'dealer.detail' => function($q){
            $q->select(['user_id', 'image', 'phone', 'address', 'city']);
        }, 'brand' => function($q){
            $q->select(['id', 'name', 'logo']);
        }, 'model' => function($q){
            $q->select(['id', 'name']);
        }])
        ->where('id', $request->car_id)
        ->first();

        return $this->responseJson(200, 'Car Detail Fetched!', $car);
    }

    protected static function uploadToS3($image, $size, $dealer_id){
        if($size == 'thumbnail'){
            $thumbnail = Image::make($image)->fit(500, 250)->encode('jpg', 50);
            $imageName = time() . '-' . $image->getClientOriginalName();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/'.$size.'/'. $imageName, $thumbnail , 'public');
            return Storage::disk('s3')->url('car/d_' .$dealer_id.'/'.$size.'/'. $imageName);
        }else{
            $imageName = time() . '-' . $image->getClientOriginalName();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/'.$size.'/'. $imageName, file_get_contents($image), 'public');
            return Storage::disk('s3')->url('car/d_' .$dealer_id.'/'.$size.'/'. $imageName);
        }
    }
}
