<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.customer.index', compact('users'));
    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.customer.form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'address' => 'required',
            'postal' => 'required',
            'status' => 'required',
            'block_reason' => 'required_if:status,0'

        ];

        $validator = Validator::make($request->all(), $rules, [
            'address.required' => 'Address is required',
            'postal.required' => 'Postal is required',
            'block_reason.required_if' => 'Block Reason is required'

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // This preserves the input data for the user
        }

        $user->address = $request->address;
        $user->postal = $request->postal;
        $user->status = $request->status;
        $user->block_reason = $request->block_reason ?? "";
        $user->save();

        if($user){
            $request->session()->flash('alert-success', 'Customer updated!');
            return redirect()->route('admin.customers.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }
}
