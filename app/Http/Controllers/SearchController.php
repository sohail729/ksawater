<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $keywords = !empty($request->keywords) ? explode(' ', $request->keywords) : "";
        $featured_only = $request->filter['featured_only'] ?? 0;
        $verified_only = $request->filter['verified_only'] ?? 0;
        $available = $request->filter['available'] ?? 0;
        $transmission = $request->filter['transmission'] ?? [];
        $fuel_type = $request->filter['fuel_type'] ?? [];
        $city = !empty($request->city) ? $request->city : $request->filter['city'] ?? "all";
        $brand_id = !empty($request->brand_id) ? $request->brand_id : $request->filter['brand_id'] ?? "";
        $model_id = !empty($request->model_id) ? $request->model_id : $request->filter['model_id'] ?? "";
        $Ctype = !empty($request->Ctype) ? $request->Ctype : $request->filter['Ctype'] ?? "";
        $from_year = $request->filter['from_year'] ?? "2000";
        $to_year = $request->filter['to_year'] ?? date('Y');
        $from_price = $request->filter['from_price'] ?? 0;
        $to_price = $request->filter['to_price'] ?? 500;
        $no_of_seats = $request->filter['no_of_seats'] ?? "";
        $power_size = $request->filter['power_size'] ?? "";
        $sortBy = $request->filter['sortBy'] ?? "";
        $page = $request->page ?? 1;
        $perPage = 6;

        $cities = DB::table('cities')->where('status', 1)->orderBy('name')->get();

        $carTypes = CarType::all();

        if (cache()->has('carBrandsModels')) {
            $carBrandsModels = cache('carBrandsModels');
        } else {
            $carBrandsModels = CarBrand::select(['id', 'name'])->with('models')->get();
            cache(['carBrandsModels' => $carBrandsModels]);
        }

        $ad1 = Advertisement::select('title', 'status', 'position', 'image')->where('position', 's1')->where('status', 1)->latest()->first();

        if(!empty($featured_only)){
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
            // ->when($brand_id, function ($query) use ($brand_id) {
            //     return $query->where('cars.brand_id', $brand_id);
            // })
            // ->when($model_id, function ($query) use ($model_id) {
            //     return $query->where('cars.model_id', $model_id);
            // })
            ->when($no_of_seats, function ($query) use ($no_of_seats) {
                return $query->where('cars.seats', $no_of_seats);
            })
            ->when($transmission, function ($query) use ($transmission) {
                return $query->whereIn('cars.transmission', $transmission);
            })
            ->when($fuel_type, function ($query) use ($fuel_type) {
                return $query->whereIn('cars.fuel_type', $fuel_type);
            })
            ->when($power_size, function ($query) use ($power_size) {
                return $query->where('cars.power_size', $power_size);
            })
            ->when($available, function ($query) use ($no_of_seats) {
                return $query->where('cars.status', 1);
            })

            ->whereBetween('cars.year', [$from_year, $to_year])
            ->whereBetween('cars.cost_per_day', [$from_price, $to_price])
            ->get();

            $featured_cars = $featured_cars->sortBy(function ($car) use ($sortBy) {
                if ($sortBy == 'price_asc') {
                    return $car->cost_per_day;
                } elseif ($sortBy == 'price_desc') {
                    return -$car->cost_per_day;
                }
            });

            $featured_cars = new LengthAwarePaginator(
                $featured_cars->forPage($page, $perPage),
                $featured_cars->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
            // ->where('cars.brand_id', $brand_id)
            // ->where('cars.type', $Ctype)
            // // ->where('cars.model_id', $model_id)
            // ->when($model_id, function ($query) use ($model_id) {
            //     return $query->whereHas('model', function ($subQuery) use ($model_id) {
            //         $subQuery->where('id', $model_id);
            //     });
            // })

            return view('searchpage', compact('cities', 'carTypes', 'featured_cars', 'carBrandsModels', 'ad1'));
        }else{

            // dd($model_id);
            // $featured_cars = Car::select('cars.*')->with(['user.detail', 'images'])
            // ->join('car_brands', 'cars.brand_id', '=', 'car_brands.id')
            // ->join('car_models', 'cars.model_id', '=', 'car_models.id')
            // ->where('cars.is_active', 1)
            // ->when($city != 'all', function ($query) use ($city) {
            //     return $query->whereHas('user.detail', function ($q) use ($city) {
            //             return $q->where('city', $city);
            //     });
            // })
            // ->whereHas('featured', function ($q) use ($brand_id, $model_id) {
            //     $q->when($brand_id || $model_id, function ($query) use ($brand_id, $model_id) {
            //         return $query->where('cars.brand_id', $brand_id)->orWhere('cars.model_id', $model_id);
            //     });
            // })
            // ->when($Ctype, function ($query) use ($Ctype) {
            //     return $query->where('cars.type', $Ctype);
            // })
            // // ->when($brand_id, function ($query) use ($brand_id) {
            // //     return $query->where('cars.brand_id', $brand_id);
            // // })
            // // ->when($model_id, function ($query) use ($model_id) {
            // //     return $query->where('cars.model_id', $model_id);
            // // })
            // ->when($no_of_seats, function ($query) use ($no_of_seats) {
            //     return $query->where('cars.seats', $no_of_seats);
            // })
            // ->when($transmission, function ($query) use ($transmission) {
            //     return $query->whereIn('cars.transmission', $transmission);
            // })
            // ->when($fuel_type, function ($query) use ($fuel_type) {
            //     return $query->whereIn('cars.fuel_type', $fuel_type);
            // })
            // ->when($available, function ($query) use ($available) {
            //     return $query->where('cars.status', 1);
            // })
            // ->whereBetween('year', [$from_year, $to_year])
            // ->whereBetween('cost_per_day', [$from_price, $to_price])
            // ->limit(3)
            // ->get();

            // dd($featured_cars);
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
            ->when($power_size, function ($query) use ($power_size) {
                return $query->where('cars.power_size', $power_size);
            })
            ->when($available, function ($query) use ($no_of_seats) {
                return $query->where('cars.status', 1);
            })
            ->whereBetween('cars.year', [$from_year, $to_year])
            ->whereBetween('cars.cost_per_day', [$from_price, $to_price])
            ->get();

            $cars = $cars->sortBy(function ($car) use ($sortBy) {
                if ($sortBy == 'price_asc') {
                    return $car->cost_per_day;
                } elseif ($sortBy == 'price_desc') {
                    return -$car->cost_per_day;
                }
            });

            $cars = new LengthAwarePaginator(
                $cars->forPage($page, $perPage),
                $cars->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
            return view('searchpage', compact('cities', 'carTypes', 'cars', 'carBrandsModels', 'ad1'));
        }
    }

}
