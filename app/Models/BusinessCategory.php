<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model {
    use HasFactory;
    protected $guarded = [];

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_id');
    }
    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function subcategories(){
        return $this->hasMany(BusinessSubCategory::class,'category_id');
    }

    public function products(){
        return $this->hasMany(BusinessProduct::class,'category_id');
    }
}
