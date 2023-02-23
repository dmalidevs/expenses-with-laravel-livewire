<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ExpensesType extends Model {
    use HasFactory;
    protected $fillable = ['code', 'name', 'slug', 'policy', 'visibility', 'assigned_id', 'updated_id'];

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_id');
    }
    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}
