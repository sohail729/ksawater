<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarType;
use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function autocomplete(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = [];
        $car_brands = CarBrand::where('name', 'like', '%' . $keyword . '%')->get();
        if($car_brands->isNotEmpty()){
            foreach ($car_brands as $brand) {
                $results[] = ['brand_id' => $brand->id, 'name' => $brand->name ];
                if ($brand->models->isNotEmpty()) {
                    foreach ($brand->models as $model) {
                        $results[] = ['brand_id' => $brand->id, 'model_id' => $model->id, 'name' => $brand->name . ' ' . $model->name];
                    }
                }
            }
        }
        else{
            $car_models = CarModel::where('name', 'like', '%' . $keyword . '%')->get();
            foreach ($car_models as $model) {
                if ($model->brand) {
                    $results[] = ['brand_id' => $model->brand->id, 'model_id' => $model->id, 'name' => $model->brand->name . ' ' . $model->name];
                }
            }
        }
        return response()->json($results);
    }

    public function incrementBookCallCount(Request $request)
    {
        $car = Car::find($request->cid);
        if(!empty($car)){
            $car->increment('clicks');
        }
    }

    public function list (Request $request)
    {
        $page_limit = $request->query('page_limit', 4);
        $current_page = $request->query('current_page', 1);
        $featured_only = $request->featured_only ?? 0;
        $available = !empty($request->available) ? 1 : 0;
        $transmission = $request->transmission ?? [];
        $fuel_type = $request->fuel_type ?? [];
        $city = !empty($request->city) ? $request->city : "all";
        $brand_id = !empty($request->brand_id) ? $request->brand_id : "";
        $model_id = !empty($request->model_id) ? $request->model_id : "";
        $Ctype = !empty($request->type) ? $request->type : "";
        $from_year = $request->from_year ?? "2000";
        $to_year = $request->to_year ?? date('Y');
        $from_price = !empty($request->from_price) ? (int) $request->from_price : 0;
        $to_price = !empty($request->to_price) ? (int) $request->to_price : 999;
        $no_of_seats = $request->no_of_seats ?? "";

        if(!empty($featured_only) && $featured_only == 1){
            $featured_cars = Car::select('cars.*')->with(['user.detail', 'images'])
            ->join('car_brands', 'cars.brand_id', '=', 'car_brands.id')
            ->join('car_models', 'cars.model_id', '=', 'car_models.id')
            ->where('cars.is_active', 1)
            ->when($city != 'all', function ($query) use ($city) {
                return $query->whereHas('user.detail', function ($q) use ($city) {
                        return $q->where('city', $city);
                });
            })
            ->whereHas('featured', function ($q) use ($brand_id, $model_id) {
                $q->when($brand_id || $model_id, function ($query) use ($brand_id, $model_id) {
                    return $query->where('cars.brand_id', $brand_id)->orWhere('cars.model_id', $model_id);
                });
            })
            ->when($Ctype, function ($query) use ($Ctype) {
                return $query->where('cars.type', $Ctype);
            })
            ->when($no_of_seats, function ($query) use ($no_of_seats) {
                return $query->where('cars.seats', $no_of_seats);
            })
            ->when($transmission, function ($query) use ($transmission) {
                return $query->whereIn('cars.transmission', $transmission);
            })
            ->when($fuel_type, function ($query) use ($fuel_type) {
                return $query->whereIn('cars.fuel_type', $fuel_type);
            })
            ->when($available == 1, function ($query) use ($no_of_seats) {
                return $query->where('cars.status', 1);
            })

            ->whereBetween('year', [$from_year, $to_year])
            ->whereBetween('cost_per_day', [$from_price, $to_price])
            ->simplePaginate($page_limit, ['*'], 'page', $current_page);

            return $this->responseJson(200, null, ['featured_cars' => $featured_cars]);
        }else{

            $cars = Car::select('cars.*')->with(['user.detail', 'images'])
            ->join('car_brands', 'cars.brand_id', '=', 'car_brands.id')
            ->join('car_models', 'cars.model_id', '=', 'car_models.id')
            ->where('cars.is_active', 1)
            ->when($city != 'all', function ($query) use ($city) {
                return $query->whereHas('user.detail', function ($q) use ($city) {
                        return $q->where('city', $city);
                });
            })
            ->when($Ctype, function ($query) use ($Ctype) {
                return $query->where('cars.type', $Ctype);
            })
            ->when($brand_id, function ($query) use ($brand_id) {
                return $query->where('cars.brand_id', $brand_id);
            })
            ->when($model_id, function ($query) use ($model_id) {
                return $query->where('cars.model_id', $model_id);
            })
            ->when($no_of_seats, function ($query) use ($no_of_seats) {
                return $query->where('cars.seats', $no_of_seats);
            })
            ->when($transmission, function ($query) use ($transmission) {
                return $query->whereIn('cars.transmission', $transmission);
            })
            ->when($fuel_type, function ($query) use ($fuel_type) {
                return $query->whereIn('cars.fuel_type', $fuel_type);
            })
            ->when($available == 1, function ($query) use ($no_of_seats) {
                return $query->where('cars.status', 1);
            })
            ->whereBetween('year', [$from_year, $to_year])
            ->whereBetween('cost_per_day', [$from_price, $to_price])
            ->simplePaginate($page_limit, ['*'], 'page', $current_page);

            return $this->responseJson(200, null, ['cars' => $cars->Items()]);
        }
    }

}
