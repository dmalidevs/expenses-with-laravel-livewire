<?php

namespace App\Models\Expenses;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\ExpensesType;

class ExpensesBill extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'billing_date' => 'datetime:Y-m-d',
    ];
    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_id');
    }
    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_id');
    }
    public function expensesTypeId() {
        return $this->belongsTo(ExpensesType::class, 'type_id');
    }
    
}
