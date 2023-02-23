<?php
namespace App\Http\Livewire\Business;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\BusinessCategory;
use App\Models\BusinessProduct;
use App\Models\BusinessSubCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class BusinessProducts extends Component
{
    use WithFileUploads;
    public $dataAddModalOpen;
    public $dataEditModalOpen;
    public $businessCategories,$businessSubcategories,$businessProducts, $targetedData;
    public $product_name, $product_details,$product_price,$product_quantity,$product_image,$addCategory, $addSubcategory, $edit_product_name, $edit_product_details, $edit_product_price, $edit_product_quantity,$edit_product_image, $editCategory, $editSubcategory, $edit_preview_image;

    public $test;

    protected $rules = [
        'addCategory' => 'required | exists:business_categories,code',
        'addSubcategory' => 'required | exists:business_sub_categories,code',
        'product_name' => 'required | string | between:3,100',
        'product_details' => 'required | string | between:3,1000',
        'product_price' => 'required | numeric',
        'product_quantity' => 'required | numeric',
        'product_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
    ];
    protected $messages = [];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }
    public function dataAddModal(){
        $this->resetErrorBag();
        $this->dataAddModalOpen = true;
    }
    public function dataEditModal($code) {
        $this->resetErrorBag();
        $this->dataEditModalOpen = true;

        $checkData = BusinessProduct::where('code',$code)->first();

        $this->targetedData = $checkData;
        $this->businessSubcategories = $this->businessCategories->where('code',$checkData->categories->code)->first()->subcategories;
        $this->editCategory = $checkData->categories->code;
        $this->editSubcategory = $checkData->subcategories->code;
        $this->edit_product_name = $checkData->name;
        $this->edit_product_details = $checkData->details;
        $this->edit_product_price = $checkData->price;
        $this->edit_product_quantity = $checkData->quantity;
        $this->edit_preview_image = $checkData->image;
    }
    public function create(){
        $this->validate();
        $checkCatData = BusinessCategory::where('code',$this->addCategory)->first();
        $checkSubcatData = BusinessSubCategory::where('code',$this->addSubcategory)->first();

        $image = $this->product_image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) .'.'. $extention;
            $image->storeAs('product_images', $newName);
            $path = 'product_images/' . $newName;
        }

        BusinessProduct::create([
            'code' => time(),
            'name' => Str::title($this->product_name),
            'details' => Str::title($this->product_details),
            'price' => $this->product_price,
            'quantity' => $this->product_quantity,
            'image' => $path ?? null,
            'category_id' => $checkCatData->id,
            'subcategory_id' => $checkSubcatData->id,
            'assigned_id' => Auth::id(),
        ]);

        $this->reset();
        session()->flash('success', 'Data Added Successfully');
        $this->mount();
    }
    public function status($code) {
        $checkData = $this->businessProducts->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->update([
            'status' => ($checkData->status == true ? false : true),
        ]);
        $this->reset();
        session()->flash('success', 'Visibility Status Updated');
        $this->mount();
    }
    public function edit(){

        $this->validate([
            'editCategory' => 'required | exists:business_categories,code',
            'editSubcategory' => 'required | exists:business_sub_categories,code',
            'edit_product_name' => 'required | string | between:3,100',
            'edit_product_details' => 'required | string | between:3,1000',
            'edit_product_price' => 'required | numeric',
            'edit_product_quantity' => 'required | numeric',
            'edit_product_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
        ]);
        // return $this->test = 'Hello';

        $checkCatData = BusinessCategory::where('code',$this->editCategory)->first();
        $checkSubcatData = BusinessSubcategory::where('code',$this->editSubcategory)->first();
        $image = $this->edit_product_image;
        $path = $this->targetedData->image;
        if ($image) {
            $extention =  $image->getClientOriginalExtension();
            $newName = Str::random(10) . '.' . $extention;
            $image->storeAs('product_images', $newName);

            if ($path) {
                if (Storage::exists(str_replace('storage/app/', '', $path))) {
                    Storage::delete(str_replace('storage/app/', '', $path));
                }
            }
            $path = 'product_images/' . $newName;
        }
        $this->targetedData->update([
            'name' => Str::title($this->edit_product_name),
            'details' => $this->edit_product_details,
            'price' => $this->edit_product_price,
            'quantity' => $this->edit_product_quantity,
            'image' => $path,
            'category_id' => $checkCatData->id,
            'subcategory_id' => $checkSubcatData->id,
            'updated_id' => Auth::id(),
        ]);
        $this->reset();
        session()->flash('success','Data Updated Successfully');
        $this->mount();
    }
    public function updatedAddCategory($value){
        $this->businessSubcategories = $this->businessCategories->where('code',$value)->first()->subcategories;
    }
    public function updatedEditCategory($value){
        $this->businessSubcategories = $this->businessCategories->where('code',$value)->first()->subcategories;
    }
    public function delete($code) {
        $checkData = $this->businessProducts->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->delete();
        $this->reset();
        session()->flash('success', 'Data Deleted Successfully');
        $this->mount();
    }
    public function mount() {
        $this->businessCategories = BusinessCategory::with('subcategories:id,code,name')->where('status',1)->select('id','code','name')->get();

        $this->businessSubcategories = collect();

        $this->businessProducts = BusinessProduct::latest()->with('assignedUser:id,name', 'updatedUser:id,name','categories:id,name','subcategories:id,name')->get();


        // $this->businessCategories = BusinessCategory::with('subcategories:id,code,name')->where('status',1)->get();
        // $this->businessSubcategories = BusinessSubcategory::with('categories:id,code,name')->where('status',1)->get();
        // $this->businessSubcategories = BusinessSubCategory::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->get();
    }
    public function render() {
        return view('livewire.business.business-products');
    }
}
