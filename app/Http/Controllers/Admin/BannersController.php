<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::orderByDesc('created_at')->get();
        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner.form');
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
            'image' => 'required|image',
        ],
        [
            'image.image' => 'The file must be a valid image.',
        ]);

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->status = $request->status;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/banners', $filename, 'public');
            $banner->image =  $filename;
        }

        $banner->save();
        if($banner){
            $request->session()->flash('alert-success', 'Banner saved successfully!');
            return redirect()->route('admin.banner.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.form', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        if(!empty($request->image)){
            $request->validate([
                'image' => 'required|image',
            ]);
        }

        $banner->title = $request->title;
        $banner->status = $request->status;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = uniqid(time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/banners', $filename, 'public');
            unlink(public_path('storage/uploads/banners/'. $banner->image));
            $banner->image =  $filename;
        }

        $banner->save();
        if($banner){
            $request->session()->flash('alert-success', 'Banner updated!');
            return redirect()->route('admin.banner.index');
        }
        $request->session()->flash('alert-danger', 'Something went wrong!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if($banner->delete()){
            return true;
        }
        return false;
    }
}
