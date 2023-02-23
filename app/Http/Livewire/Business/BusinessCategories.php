<?php

namespace App\Http\Livewire\Business;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class BusinessCategories extends Component {
    use WithFileUploads;
    public $dataAddModalOpen;
    public $dataEditModalOpen;
    public $businessCategories;
    public $category_name, $category_image, $edit_category_name, $edit_category_image;
    public $targetedData;
    public $test;
    protected $rules = [
        'category_name' => 'required | string | between:3,100',
        'category_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
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

        $checkData = $this->businessCategories->where('code', $code)->first();
        if (!$checkData) {
        }

        $this->targetedData = $checkData;
        $this->edit_category_name = $checkData->name;
        $this->edit_category_image = $checkData->image;
    }
    public function create() {
        $this->validate();
        $image = $this->category_image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) .'.'. $extention;
            $image->storeAs('category_images', $newName);
            $path = 'category_images/' . $newName;
        }
        BusinessCategory::create([
            'code' => time(),
            'name' => Str::title($this->category_name),
            'slug' => Str::slug($this->category_name),
            'image' => $path ?? null,
            'assigned_id' => Auth::id(),
        ]);

        $this->reset();
        session()->flash('success', 'Data Added Successfully');
        $this->mount();
    }
    public function status($code) {
        $checkData = $this->businessCategories->where('code', $code)->first();
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
            'edit_category_name' => 'required | string | between:3,100',
            'edit_category_image' => 'nullable | image | mimes:jpg,jpeg,png,gif',
        ]);
        $image = $this->edit_category_image;
        $path = $this->targetedData->image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) . '.' . $extention;
            $image->storeAs('category_images', $newName);
            
            if ($path) {
                if (Storage::exists(str_replace('storage/app/', '', $path))) {
                    Storage::delete(str_replace('storage/app/', '', $path));
                }
            }
            $path = 'category_images/' . $newName;
        }
        
        $this->targetedData->update([
            'name' => Str::title($this->edit_category_name),
            'slug' => Str::slug($this->edit_category_name),
            'image' => $path,
            'updated_id' => Auth::id(),
        ]);
        $this->reset();
        session()->flash('success', 'Data Updated Successfully');
        $this->mount();
    }
    public function delete($code) {
        $checkData = $this->businessCategories->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->delete();
        session()->flash('success', 'Data Deleted Successfully');
        $this->mount();
    }
    public function mount() {
        $this->businessCategories = BusinessCategory::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->get();
    }
    public function render() {
        return view('livewire.business.business-category');
    }
}
