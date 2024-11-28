<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class CarBrandController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = CarBrand::all();
        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::where('type', 'CarBrand')->pluck('name','id');
        return view('admin.brand.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $brand = new CarBrand();
        $brand->name = $request->name;
        $brand->is_top = $request->is_top ?? 0;
        $brand->description = $request->description;
        if(!empty($request->logo)){
            $brand->logo = uploadFileToS3($request->logo, 'brand');
        }
        $brand->save();
        if($brand){
            $request->session()->flash('alert-success', 'Brand saved successfully!');
            return redirect()->route('admin.brands.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarBrand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(CarBrand $brand)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarBrand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(CarBrand $brand)
    {
        return view('admin.brand.form', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarBrand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarBrand $brand)
    {
        $brand->name = $request->name;
        $brand->is_top = $request->is_top ?? 0;
        $brand->description = $request->description;
        if(!empty($request->logo)){
            $brand->logo = uploadFileToS3($request->logo, 'brand');
        }
        $brand->save();
        if($brand){
            $request->session()->flash('alert-success', 'Brand updated!');
            return redirect()->route('admin.brands.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarBrand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarBrand $brand)
    {
        if($brand->delete()){
            return true;
        }
        return false;
    }
}
