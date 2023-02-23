<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Expenses\ExpensesBill;
use App\Models\Expenses\ExpensesType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Stringable;

class ExpensesBillLivewire extends Component {
    use WithFileUploads;

    public $expensesBill;
    public $expensesTypes;
    public $purpose,$details,$amount,$invoice_image,$billing_date, $expensesType;

    public $dataAddModalOpen;
    public $dataEditModalOpen;
    protected $rules = [
        'expensesType' => 'required | exists:expenses_types,code',
        'purpose' => 'required | string | between:5,100',
        'details' => 'required | string | between:5,500',
        'amount' => 'required | integer | between:1,1000',
        'invoice_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
        'billing_date' => 'required | date',
    ];
    protected $messages = [
        'purpose.required' => 'Purpose Required',
        'purpose.between' => 'Purpose will be in 5 to 100 characters',
    ];
    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }
    public function dataAddModal(){
        $this->resetErrorBag();
        $this->dataAddModalOpen = true;
    }
    public function dataEditModal($code){
        $this->resetErrorBag();
        $this->dataEditModalOpen = true;
    }
    public function create() {
        $this->validate();
        $types = ExpensesType::where('code',$this->expensesType)->first();
        if ($this->invoice_image) {
            $extention =  $this->invoice_image->getClientOriginalExtention();
            $newName = Str::random(10).$extention;
            $this->invoice_image->storeAs('invoice_images',$newName);
            $path = 'invoice_images/'.$newName;
        }
        ExpensesBill::create([
            'code' => time(),
            'purpose' => $this->purpose,
            'details' => $this->details,
            'amount' => $this->amount,
            'type_id' => $types->id,
            'invoice_image' => $path ?? null,
            'billing_date' => $this->billing_date,
            'assigned_id' => Auth::id(),
        ]);
        
        $this->reset();
        return session()->flash('success', 'Data Added Successfully');
        $this->mount();
    }
    public function delete($code) {
        $checkData = $this->expensesBill->where('code', $code)->first();
        if (!$checkData) {
            return session()->flash('error', 'Invalid Code');
        }
        $checkData->delete();
        return session()->flash('success', 'Data Deleted Successfully');
    }
    public function mount() {
        $this->expensesBill = ExpensesBill::latest()->with('assignedUser:id,name', 'updatedUser:id,name', 'expensesTypeId:id,name,policy')->get();

        $this->billing_date = today()->format('Y-m-d');
        $this->expensesTypes = ExpensesType::select('id','code', 'name', 'policy')->get();

    }
    public function render() {
        return view('livewire.expenses.expenses-bill-livewire');
    }
}
