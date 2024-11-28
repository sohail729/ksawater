<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Team::where('type', 'rider')->orderByDesc('created_at')->get();
        return view('admin.driver.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.driver.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'fullname' => 'required|string',
            'email' => 'required|email:rfc,dns,strict|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:3',
            'national_id' => 'required',
            'vehicle_number' => 'required',
            'license_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'fullname.required' => 'Fullname is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email must be unique',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'national_id.required' => 'National Id is required',
            'vehicle_number.required' => 'Vehicle Number is required',
            'license_number.required' => 'License Number is required'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }
        $driver = new Team();
        $driver->fullname = $request->fullname;
        $driver->phone = $request->phone;
        $driver->email = $request->email;
        $driver->password = $request->password;
        $driver->national_id = $request->national_id;
        $driver->vehicle_number = $request->vehicle_number;
        $driver->license_number = $request->license_number;
        $driver->created_at = now()->format('Y-m-d H:i:s');
        $driver->save();

        if($driver){
            $request->session()->flash('alert-success', 'Driver account created!');
            return redirect()->route('admin.drivers.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Team $driver)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $driver)
    {
        return view('admin.driver.form', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $driver)
    {
        $rules = [
            'fullname' => 'required|string',
            'email' => 'required|email:rfc,dns,strict|unique:users,email,' . $driver->id,
            'phone' => 'required',
            'password' => 'required|min:3',
            'national_id' => 'required',
            'vehicle_number' => 'required',
            'license_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'fullname.required' => 'Fullname is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email must be unique',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'national_id.required' => 'National Id is required',
            'vehicle_number.required' => 'Vehicle Number is required',
            'license_number.required' => 'License Number is required'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(422, null, null, $validator->messages()->all());
        }
        $driver->fullname = $request->fullname;
        $driver->phone = $request->phone;
        $driver->email = $request->email;
        $driver->password = $request->password;
        $driver->national_id = $request->national_id;
        $driver->vehicle_number = $request->vehicle_number;
        $driver->license_number = $request->license_number;
        $driver->status = $request->status;
        $driver->save();

        if($driver){
            $request->session()->flash('alert-success', 'Driver updated!');
            return redirect()->route('admin.drivers.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $driver)
    {
        if($driver->delete()){
            return true;
        }
        return false;
    }
}
