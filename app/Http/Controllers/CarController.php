<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\FeaturedCar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CarController extends Controller
{
    public function form()
    {
        $brandsWithModels = CarBrand::with(['models' => function($q){
            $q->orderBy('name', 'asc')->get();
        }])->orderBy('name', 'asc')->get();
        $carBrands = $brandsWithModels->map(function ($brand) {
            return ['id' => $brand->id, 'name' => $brand->name, 'models' => $brand->models->map(fn($model) => ['id' => $model->id, 'name' => $model->name])->toArray()];
        })->toArray();

        $carTypes = CarType::all();

        return view('dashboard.create-car', compact('carBrands', 'carTypes'));
    }

    public function list (Request $request)
    {
        $cars  = Car::query();
        $cars->with('images')->where('user_id', auth()->user()->id);
        if($request->type == 1){
            $cars = $cars->where('status', 1);
        }else if($request->type == 2){
            $cars = $cars->where('status', 2);
        }else if($request->type == 3){
            $cars = $cars->where('status', 3);
        }else if($request->type == 'featured'){
            $cars = $cars->whereHas('featured', function ($query) {
                $query->where('status', 1);
            });
        }
        $cars = $cars->paginate(5);

        $allCarsCount = Car::getAllCarsCount(auth()->user()->id);
        $availCarsCount = Car::getAvailableCarsCount(auth()->user()->id);
        $rentedCarsCount = Car::getRentedCarsCount(auth()->user()->id);

        return view('dashboard.index', compact('cars', 'allCarsCount', 'availCarsCount', 'rentedCarsCount'));
    }

    public function changeStatus(Request $request)
    {
        try {
            Car::where('id', $request->id)->update(['status' => $request->status]);
            return response()->json(['status' => 200, 'message' => 'Status Updated!']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 400, 'message' => 'Something went wrong!']);
        }

    }

    public function addNew(Request $request)
    {

       try {
        $carObj = new Car();
        $carObj->user_id = auth()->user()->id;
        $carObj->delivery_possible = $request->delivery_possible;
        $carObj->insurance_included = $request->insurance_included;
        $carObj->pickup_location = $request->location;
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
        $carObj->status = $request->status;
        $carObj->is_active = 1;
        $carObj->save();

        if ($request->has('is_featured')) {
            $featuredCar = new FeaturedCar();
            $featuredCar->dealer_id = auth()->user()->id;
            $featuredCar->car_id = $carObj->id;
            $featuredCar->save();
        }

        if(!empty($request->images)){
            foreach($request->images as $key => $image){
                CarImage::create([
                    'car_id' => $carObj->id,
                    'thumbnail' => self::uploadToS3($image, 'thumbnail', auth()->user()->id),
                    'full' => self::uploadToS3($image, 'full', auth()->user()->id)

                ]);
            }
        }
         return response()->json(['status' => 200, 'message' => 'Car Listed!']);
       } catch (Exception $e) {
        return response()->json(['status' => 400, 'message' => 'Something went wrong!']);
       }

    }

    public function toggleFeatured(Request $request)
    {
        $featured = FeaturedCar::where(['car_id' => $request->id, 'dealer_id' => $request->dealer])->exists();
        if($featured){
            FeaturedCar::where(['car_id' => $request->id, 'dealer_id' => $request->dealer])->update(['status' => $request->status]);
        }else{
            $featuredCar = new FeaturedCar();
            $featuredCar->dealer_id = $request->dealer;
            $featuredCar->car_id =  $request->id;
            $featuredCar->save();
        }
        return response()->json(['status' => 200, 'message' => 'Status Changed!']);
    }

    protected static function uploadToS3($image, $size, $dealer_id){
        if($size == 'thumbnail'){
            $thumbnail = Image::make($image)->fit(500, 250)->encode('jpg', 50);
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/'.$size.'/'. $imageName, $thumbnail , 'public');
            return Storage::disk('s3')->url('car/d_' .$dealer_id.'/'.$size.'/'. $imageName);
        }else{
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('s3')->put('car/d_'. $dealer_id.'/'.$size.'/'. $imageName, file_get_contents($image), 'public');
            return Storage::disk('s3')->url('car/d_' .$dealer_id.'/'.$size.'/'. $imageName);
        }
    }
}
