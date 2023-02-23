<?php

namespace App\Http\Livewire\Business;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\BusinessCategory;
use App\Models\BusinessSubCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class BusinessSubcategories extends Component {
    use WithFileUploads;
    public $dataAddModalOpen;
    public $dataEditModalOpen;
    public $businessSubcategories;
    public $businessCategories;
    public $subcategory_name, $subcategory_image, $addCategory, $edit_category, $edit_subcategory_name, $edit_subcategory_image, $edit_preview_image;
    public $targetedData;
    protected $rules = [
        'addCategory' => 'required | exists:business_categories,code',
        'subcategory_name' => 'required | string | between:3,100',
        'subcategory_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
    ];
    protected $messages = [];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }
    public function dataAddModal() {
        $this->resetErrorBag();
        $this->dataAddModalOpen = true;
    }
    public function dataEditModal($code) {
        $this->resetErrorBag();
        $this->dataEditModalOpen = true;

        $checkData = BusinessSubcategory::where('code', $code)->first();

        $this->targetedData = $checkData;
        //$this->businessCategories = BusinessCategory::where('code',$this->edit_category)->first();
        $this->edit_subcategory_name = $checkData->name;
        $this->edit_preview_image = $checkData->image;
        $this->edit_category = $checkData->categories->code;
    }
    public function create() {
        $this->validate();
        $checkData = BusinessCategory::where('code', $this->addCategory)->first();
        $image = $this->subcategory_image;

        $image = $this->subcategory_image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) . '.' . $extention;
            $image->storeAs('subcategory_images', $newName);

            $path = 'subcategory_images/'.$newName;
        }
        BusinessSubcategory::create([
            'code' => time(),
            'name' => Str::title($this->subcategory_name),
            'slug' => Str::slug($this->subcategory_name),
            'image' => $path ?? null,
            'category_id' => $checkData->id,
            'assigned_id' => Auth::id(),
        ]);

        $this->reset();
        session()->flash('success', 'Data Added Successfully');
        $this->mount();
    }

    public function status($code) {
        $checkData = $this->businessSubcategories->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->update([
            'status' => ($checkData->status == true ? false : true),
        ]);
        session()->flash('success', 'Visibility Status Updated');
        $this->mount();
    }
    public function edit() {
        $this->validate([
            'edit_category' => 'required | exists:business_categories,code',
            'edit_subcategory_name' => 'required | string | between:3,100',
            'edit_subcategory_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $checkCatData = BusinessCategory::where('code', $this->edit_category)->first();
        $image = $this->edit_subcategory_image;
        $path = $this->targetedData->image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) . '.' . $extention;
            $image->storeAs('subcategory_images', $newName);

            if ($path) {
                if (Storage::exists(str_replace('storage/app/', '', $path))) {
                    Storage::delete(str_replace('storage/app/', '', $path));
                }
            }
            $path = 'subcategory_images/' . $newName;
        }
        $this->targetedData->update([
            'name' => Str::title($this->edit_subcategory_name),
            'slug' => Str::slug($this->edit_subcategory_name),
            'image' => $path ?? null,
            'category_id' => $checkCatData->id,
            'updated_id' => Auth::id(),
        ]);
        $this->reset();
        session()->flash('success', 'Data Updated Successfully');
        $this->mount();
    }
    public function delete($code) {
        $checkData = $this->businessSubcategories->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->delete();
        session()->flash('success', 'Data Deleted Successfully');
        $this->mount();
    }
    public function mount() {
        $this->businessSubcategories = BusinessSubCategory::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->get();

        $this->businessCategories = BusinessCategory::with('subcategories:id,code,name')->where('status', 1)->get();
    }
    public function render() {
        return view('livewire.business.business-subcategories');
    }
}
