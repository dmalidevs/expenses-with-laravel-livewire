<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Expenses\ExpensesType;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ExpensesTypeLivewire extends Component {

    public $expensesType;
    public $addName, $addType, $editName, $editType;
    public $dataEditModalOpen;
    public $targetedData;
    
    public $dataAddModalOpen;
    public function dataAddModal() {
        $this->resetErrorBag();
        $this->dataAddModalOpen = true;
    }

    protected $rules = [
        'addName' => 'required | string | between:3,30 | unique:expenses_types,name',
        'addType' => 'required | in:credit,debit',
    ];
    protected $messages = [
        'addName.required' => 'Type Required',
        'addName.between' => 'Type will be 3 - 30 characters',
        'addName.unique' => 'Already Added',
    ];
    
    public function dataEditModal($code){
        $this->resetErrorBag();

        $checkData = $this->expensesType->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $this->targetedData = $checkData;

        $this->editName = $checkData->name;
        $this->editType = $checkData->policy;

        $this->dataEditModalOpen = true;
    }

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }
    public function createType() {
        $this->validate();
        ExpensesType::create([
            'code' => time(),
            'name' => Str::title($this->addName),
            'slug' => Str::slug($this->addName),
            'policy' => Str::title($this->addType),
            'assigned_id' => Auth::id(),
        ]);
        $this->reset();
        session()->flash('success','Type Added Successfully');
        $this->mount();
    }

    public function visibility($code) {
        $checkData = $this->expensesType->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->update([
            'visibility' => ($checkData->visibility == true ? false : true),
        ]);
        return session()->flash('success', 'Visibility Status Updated');
    }

    public function editType() {
        $this->validate([
            'editName' => ['required','string','between:3,30'],
            'editType' => 'required | in:credit,debit',
        ]);
        $this->targetedData->update([
            'name' => Str::title($this->editName),
            'slug' => Str::slug($this->editName),
            'policy' => Str::title($this->editType),
            'updated_id' => Auth::id(),
        ]);
        $this->reset();
        session()->flash('success','Type Updated Successfully');
        $this->mount();
    }
    public function deleteType($code){
        $checkData = $this->expensesType->where('code',$code)->first();
        if(!$checkData){
            return session()->flash('error','Invalid Code');
        }
        $checkData->delete();
        return session()->flash('success','Data Deleted Successfully');
    }
    public function mount(){
        $this->expensesType = ExpensesType::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->get();
    }
    public function render() {
        return view('livewire.expenses.expenses-type-livewire');
    }
}
