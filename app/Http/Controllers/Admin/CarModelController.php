<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = CarModel::all();
        return view('admin.model.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = CarBrand::all();
        return view('admin.model.form', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $model = new CarModel();
        $model->name = $request->name;
        $model->brand_id = $request->brand_id;
        $model->description = $request->description;
        $model->save();
        if($model){
            $request->session()->flash('alert-success', 'Model saved successfully!');
            return redirect()->route('admin.models.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarModel  $model
     * @return \Illuminate\Http\Response
     */
    public function show(CarModel $model)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarModel  $model
     * @return \Illuminate\Http\Response
     */
    public function edit(CarModel $model)
    {
        $brands = CarBrand::all();
        return view('admin.model.form', compact('brands', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarModel  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarModel $model)
    {
        $model->name = $request->name;
        $model->brand_id = $request->brand_id;
        $model->description = $request->description;
        $model->save();
        if($model){
            $request->session()->flash('alert-success', 'Model updated!');
            return redirect()->route('admin.models.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarModel  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarModel $model)
    {
        if($model->delete()){
            return true;
        }
        return false;
    }
}
