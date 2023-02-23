<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpensesType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpensesTypeController extends Controller {
    public function index() {
        $expensestype = ExpensesType::latest()->with('assignedUser:id,name', 'updatedUser:id,name')->paginate(10);
        return view('expenses.type.index', ['expensestype' => $expensestype]);
    }
    public function store(Request $request) {
        $request->validate([
            'expense_type' => ['required', 'string', 'between:3,30', 'unique:expenses_types,name'],
            'policy' => ['required', 'in:credit,debit'],
        ],[
            'expense_type.required' => 'Type Required',
            'expense_type.between' => 'Type will be 3 - 30 characters',
            'expense_type.unique' => 'Already Added',
        ]);
        ExpensesType::create([
            'code' => time(),
            'name' => Str::title($request->expense_type),
            'slug' => Str::slug($request->expense_type),
            'policy' => Str::title($request->policy),
            'assigned_id' => Auth::id(),
        ]);
        return back()->with('success', 'Type Added Successfully');
    }
    public function status(Request $request) {
        $checkData = ExpensesType::where('id', $request->visibilityId)->first();
        if (!$checkData) {
            return back()->with('alert', ['type' => 'error', 'message' => 'Invalid Id']);
        }
        $checkData->update(['visibility' => ($checkData->visibility == true ? false : true)]);
        return back()->with('success', 'Status Changed Successfully');
    }
    public function edit($code) {
        $code = ExpensesType::where('code', $code)->first();
        if (!$code) {
            return back()->with('alert', ['type' => 'error', 'message' => 'Invalid id']);
        }
        return view('expenses.type.edit', ['editType' => $code]);
    }
    public function update(Request $request, $code) {
        $checkCode = ExpensesType::where('code', $code)->first();
        if (!$checkCode) {
            return back()->with('error', 'Invalid Code');
        }
        $request->validate([
            'expense_type' => ['required', 'string', 'between:3,30',Rule::unique('expenses_types','name')->ignore($checkCode->id)],
            'policy' => ['required', 'in:credit,debit'],
        ],[
            'expense_type.required' => 'Type Required',
            'expense_type.between' => 'Type will be 3 - 30 characters',
            'expense_type.unique' => 'Type Already Exists',
            'policy.in' => 'Policy Must be Credit Or Debit',
        ]);
        $checkCode->update([
            'name' => Str::title($request->expense_type),
            'slug' => Str::slug($request->expense_type),
            'policy' => Str::title($request->policy),
            'updated_id' => Auth::id(),
        ]);
        return redirect('expenses/type/all')->with('success', 'Type Updated Successfully');
    }
    public function destroy(Request $request) {
        $deleteData = ExpensesType::where('id', $request->deleteId)->first();
        if (!$deleteData) {
            return back()->with('error', 'Invalid Code');
        }
        $deleteData->delete();
        return back()->with('success', 'Data Deleted Successfully');
    }
    public function active() {
        $activeTypes = ExpensesType::where('visibility', true)->get();
        return view('expenses.type.active', ['activeTypes' => $activeTypes]);
    }
}
