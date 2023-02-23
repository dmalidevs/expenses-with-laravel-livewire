<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSubCategory extends Model {
    use HasFactory;
    protected $guarded = [];

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_id');
    }
    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_id');
    }
    public function categories(){
        return $this->belongsTo(BusinessCategory::class,'category_id');
    }
}
