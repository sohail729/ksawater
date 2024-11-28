<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBrand;
use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = CarType::all();
        return view('admin.type.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.type.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $type = new CarType();
        $type->name = $request->name;
        $type->logo = uploadFileToS3($request->logo, 'type');
        $type->save();
        if($type){
            $request->session()->flash('alert-success', 'Car type saved successfully!');
            return redirect()->route('admin.types.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(CarType $type)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(CarType $type)
    {
        return view('admin.type.form', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarType $type)
    {
        $type->name = $request->name;
        if(!empty($request->logo)){
            $type->logo = uploadFileToS3($request->logo, 'type');
        }
        $type->save();
        if($type){
            $request->session()->flash('alert-success', 'Car type updated!');
            return redirect()->route('admin.types.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarType $type)
    {
        if($type->delete()){
            return true;
        }
        return false;
    }
}
