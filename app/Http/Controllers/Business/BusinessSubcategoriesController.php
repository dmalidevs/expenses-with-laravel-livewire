<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\BusinessSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class BusinessSubcategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = BusinessSubCategory::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->paginate(10);
        return view('business.subcategories.index', ['subcategories' =>$subcategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categories = BusinessCategory::select('code', 'name')->get();
        return view('business.subcategories.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        return $checkCategories = BusinessSubCategory::where('code', $request->category_id)->first();
        $request->validate([
            'category_id' => 'required | exists:business_categories,code',
            'subcategory_name' => 'required | string | between:3,100',
            'subcategory_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $image = $request->file('subcategory_image');
        if ($image) {
            $path = $image->store('subcategory_image');
        }
        BusinessSubCategory::create([
            'code' => time(),
            'name' => Str::title($request->category_name),
            'slug' => Str::slug($request->category_name),
            'image' => $path ?? null,
            'category_id' => $checkCategories->id,
            'assigned_id' => Auth::id(),
        ]);
        return back()->with('success', 'Subcategory Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request) {
        $checkData = BusinessSubcategory::where('id', $request->statusId)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Status Id');
        }

        $checkData->update([
            'status' => ($checkData->status == true ? false : true),
        ]);
        return back()->with('success', 'Status Changed Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code) {
        $checkData = BusinessSubcategory::where('code', $code)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Edit Code');
        }
        return view('business.sucategories.edit', ['categories' => $checkData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code) {
        $checkData = BusinessSubcategory::where('code', $code)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Update Code');
        }
        $request->validate([
            'subcategory_name' => 'required | string | between:3,100',
            'subcategory_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $image = $request->file('subcategory_image');
        if ($image) {
            if ($checkData->image && file_exists('storage/' . $checkData->image)) {
                unlink('storage/' . $checkData->image);
            }
            $path = $image->store('subcategory_image');
        } else {
            $path = $checkData->image;
        }
        $checkData->update([
            'name' => Str::title($request->subcategory_name),
            'slug' => Str::slug($request->subcategory_name),
            'image' => $path,
            'category_id' => $request->category_id,
            'updated_id' => Auth::id(),
        ]);
        return redirect(route('business.categories'))->with('success', 'Subcategory Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $checkData = BusinessSubcategory::where('code', $request->deleteCode)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Delete Code');
        }
        if ($checkData->image && file_exists('storage/' . $checkData->image)) {
            unlink('storage/' . $checkData->image);
        }
        $checkData->delete();
        return back()->with('success', 'Subcategory Deleted Successfully');
    }
}
