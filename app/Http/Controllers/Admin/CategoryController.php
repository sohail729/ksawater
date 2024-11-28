<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::where('type', 'Category')->pluck('name','id');
        return view('admin.category.form');
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

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/categories', $filename, 'public');
            $category->image =  $filename;
        }
        $category->save();
        if($category){
            $request->session()->flash('alert-success', 'Category saved successfully!');
            return redirect()->route('admin.category.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.category.form', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->description = $request->description;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/categories', $filename, 'public');
            unlink(public_path('storage/uploads/categories/'. $category->image));
            $category->image =  $filename;
        }
        $category->save();
        if($category){
            $request->session()->flash('alert-success', 'Category updated!');
            return redirect()->route('admin.brands.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->delete()){
            return true;
        }
        return false;
    }
}
