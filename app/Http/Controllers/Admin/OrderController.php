<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.order.index');
    }

    public function getOrdersList(Request $request)
    {
        $orders = Order::orderByDesc('order_time')->get();
        $orders->transform(function ($order) {
            $order->order_time = \Carbon\Carbon::parse($order->order_time)->format('h:i a d/M/Y');
            return $order;
        });
        $response['data'] = $orders;
        return response($response, Response::HTTP_OK)->header('Content-Type', 'application/json');
    }

    public function viewOrder(Request $request, $orderID)
    {
        $data = [];
        $order = Order::with(['detail', 'rider', 'delivery_logs.rider'])->find($orderID);
        if($order){
            $data['status'] = 200;
            $data['content'] = view('admin.order.view', compact('order'))->render();
            return response($data, Response::HTTP_OK)->header('Content-Type', 'application/json');
        }
        return response(['status' => 404], Response::HTTP_OK)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Order::where('type', 'Order')->pluck('name','id');
        return view('admin.order.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'image',
        ], [
            'file.image' => 'The file must be a valid image.',
        ]);

        $Order = new Order();
        $Order->name = $request->name;
        $Order->description = $request->description;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/categories', $filename, 'public');
            $Order->image =  $filename;
        }
        $Order->save();
        if($Order){
            $request->session()->flash('alert-success', 'Order saved successfully!');
            return redirect()->route('admin.order.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $Order)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $Order)
    {
        return view('admin.order.form', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $Order)
    {
        $Order->name = $request->name;
        $Order->description = $request->description;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/categories', $filename, 'public');
            unlink(public_path('storage/uploads/categories/'. $Order->image));
            $Order->image =  $filename;
        }
        $Order->save();
        if($Order){
            $request->session()->flash('alert-success', 'Order updated!');
            return redirect()->route('admin.brands.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $Order)
    {
        if($Order->delete()){
            return true;
        }
        return false;
    }
}
