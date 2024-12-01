<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->orderByDesc('created_at')->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'image' => 'required|image',
        // ],
        // [
        //     'image.image' => 'The file must be a valid image.',
        // ]);

        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->volume = $request->volume;
        $product->unit = $request->unit;
        $product->stock = $request->stock;
        $product->bundle = $request->bundle;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/products', $filename, 'public');
            $product->image =  $filename;
        }

        $product->save();
        if($product){
            $request->session()->flash('alert-success', 'Product saved successfully!');
            return redirect()->route('admin.product.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.form', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if(!empty($request->image)){
            $request->validate([
                'image' => 'image',
            ]);
        }

        $product->title = $request->title;
        $product->status = $request->status;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->volume = $request->volume;
        $product->unit = $request->unit;
        $product->stock = $request->stock;
        $product->bundle = $request->bundle;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/products', $filename, 'public');
            unlink(public_path('storage/uploads/products/'. $product->image));
            $product->image =  $filename;
        }
        $product->save();
        if($product){
            $request->session()->flash('alert-success', 'Product updated!');
            return redirect()->route('admin.product.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete()){
            return true;
        }
        return false;
    }
}
