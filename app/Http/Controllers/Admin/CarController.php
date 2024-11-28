<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function index (Request $request)
    {
        $cars  = Car::with('images')->get();
        return view('admin.car.index', compact('cars'));
    }

    public function show (Request $request)
    {
        $car  = Car::with('images')->whereId($request->car_id)->first();
        return view('admin.car.show', compact('car'));
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

    public function destroy($car, Request $request)
    {
        Car::find($car)->delete();
        return response()->json(['status' => 200, 'message' => 'Car Removed!']);
    }

    public function block($id, Request $request)
    {
        $car = Car::find($id);
        $car->is_active = 0;
        $car->block_reason = $request->reason ?? "Blocked Ad";
        $car->save();
        return response()->json(['status' => 200, 'message' => 'Car ad has been blocked.']);
    }

    public function unblock($id)
    {
        $car = Car::find($id);
        $car->is_active = 1;
        $car->block_reason = "";
        $car->save();
        return response()->json(['status' => 200, 'message' => 'Car ad has been Unblocked.']);
    }
}
