<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Auth;

class BusinessCategoriesController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = BusinessCategory::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->paginate(10);
        return view('business.categories.index', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required | string | between:3,100',
            'category_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $image = $request->file('category_image');
        if($image){
            $path = $image->store('category_image');
        }else{
            $path = null;
        }
        BusinessCategory::create([
            'code' => time(),
            'name' => Str::title($request->category_name),
            'slug' => Str::slug($request->category_name),
            'image' => $path,
            'assigned_id' => Auth::id(),
        ]);
        return back()->with('success','Category Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request) {
        $checkData = BusinessCategory::where('id',$request->statusId)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Status Id');
        }

        $checkData->update([
            'status' => ($checkData->status == true ? false : true),
        ]);
        return back()->with('success','Status Changed Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code) {
        $checkData = BusinessCategory::where('code',$code)->first();
        if(!$checkData){
            return back()->with('error','Invalid Edit Code');
        }
        return view('business.categories.edit',['categoriesTypes' => $checkData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code) {
        $checkData = BusinessCategory::where('code',$code)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Update Code');
        }
        $request->validate([
            'category_name' => 'required | string | between:3,100',
            'category_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $image = $request->file('category_image');
        if ($image) {
            if($checkData->image && file_exists('storage/'.$checkData->image)){
                unlink('storage/'.$checkData->image);
            }
            $path = $image->store('category_image');
        } else {
            $path = $checkData->image;
        }
        $checkData->update([
            'name' => Str::title($request->category_name),
            'slug' => Str::slug($request->category_name),
            'image' => $path,
            'updated_id' => Auth::id(),
        ]);
        return redirect(route('business.categories'))->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $checkData = BusinessCategory::where('code',$request->deleteCode)->first();
        if(!$checkData){
            return back()->with('error','Invalid Delete Code');
        }
        if($checkData->image && file_exists('storage/'.$checkData->image)){
            unlink('storage/'.$checkData->image);
        }
        $checkData->delete();
        return back()->with('success','Category Deleted Successfully');
    }
}
