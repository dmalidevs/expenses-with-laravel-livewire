<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Expenses\ExpensesBill;
use App\Models\Expenses\ExpensesType;

class ExpensesBillController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $bills = ExpensesBill::latest()->with('assignedUser:id,name', 'updatedUser:id,name','expensesTypeId:id,name,policy')->paginate(10);
        return view('expenses.bill.index', ['expensesbill' => $bills]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $expensesTypes = ExpensesType::select('code', 'name', 'policy')->get();
        return view('expenses.bill.create', ['expensesTypes' => $expensesTypes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'expensesType' => 'required | exists:expenses_types,code',
            'purpose' => 'required | string | between:5,100',
            'details' => 'required | string | between:5,500',
            'amount' => 'required | integer | between:1,1000',
            'invoice_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
            'billing_date' => ['required', 'date', 'date_format:Y-m-d', 'before:' . today()->addDays(1)],
        ]);
        $checkExpensesTypes = ExpensesType::where('code',$request->expensesType)->first();
        $image = $request->file('invoice_image');
        if($image){
            $invoice_path = $image->store('invoice_images');
        }
        ExpensesBill::create([
            'code' => time(),
            'type_id' => $checkExpensesTypes->id,
            'purpose' => Str::title($request->purpose),
            'details' => Str::title($request->details),
            'amount' => $request->amount,
            'invoice_image' => $invoice_path ?? null,
            'billing_date' => $request->billing_date,
            'assigned_id' => Auth::id(),

        ]);
        return redirect(route('expenses.bills'))->with('success', 'Bill Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code) {
        $checkData = ExpensesBill::where('code', $code)->first();
        if (!$code) {
            return back()->with('error',  'Invalid Id');
        }
        $expensesTypes = ExpensesType::select('code', 'name', 'policy')->get();
        return view('expenses.bill.edit',['editBill' => $checkData , 'expensesTypes' => $expensesTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code){
        $checkData = ExpensesBill::where('code', $code)->first();
        if (!$checkData) {
            return back()->with('error', 'Invalid Code');
        }

        $image = $request->file('invoice_image');
        if($image){
            if($checkData->invoice_image && file_exists('storage/'.$checkData->invoice_image)){
                unlink('storage/'.$checkData->invoice_image);
            }
            $invoice_path = $image->store('invoice_images');
        }else{
            $invoice_path = $checkData->invoice_image;
        }

        $request->validate([
            'expensesType' => 'required | exists:expenses_types,code',
            'purpose' => 'required | string | between:5,100',
            'details' => 'required | string | between:5,500',
            'amount' => 'required | integer | between:1,1000',
            'invoice_image' => 'nullable | image | mimes:jpeg,png,jpg,gif',
            'billing_date' => ['required', 'date','date_format:Y-m-d','before:'.today()->addDays(1)],
        ]);
        $checkData->update([
            'purpose' => Str::title($request->purpose),
            'details' => Str::title($request->details),
            'amount' => $request->amount,
            'invoice_image' => $invoice_path ?? $checkData->invoice_image,
            'billing_date' => $request->billing_date,
            'updated_id' => Auth::id(),
        ]);

        return redirect()->route('expenses.bills')->with('success','Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $deleteData = ExpensesBill::where('code', $request->deleteCode)->first();
        if (!$deleteData) {
            return back()->with('error', 'Invalid Code');
        }
        if($deleteData->invoice_image && file_exists('storage/'.$deleteData->invoice_image)){
            unlink('storage/'.$deleteData->invoice_image);
        }

        $deleteData->delete();
        return back()->with('success', 'Bill Deleted Successfully');
    }
}
