<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'name',
        'departments',
        'island',
        'region_id',
        'province_id',
        'district_id',
        'city_id',
        'municipality_id',
        'brgy_id',
        'street_address',
        'is_active',
        'modified_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latestId = $model::orderBy('id', 'DESC')->first();
            $slug = $latestId != NULL ? encrypt($latestId->id + 1) : encrypt(1);
            $model->slug = $slug;
            $model->modified_by = Auth::user()->full_name;
        });

        static::updating(function ($model) {
            $model->modified_by = Auth::user()->full_name;
        });
    }

    public function user()
    {
        return $this->hasMany(User::class, 'supplier_id');
    }

    public function department_supplier()
    {
        return $this->hasMany(DepartmentSupplier::class, 'supplier_id');
    }

    public function supplier_document()
    {
        return $this->hasMany(SupplierDocument::class, 'supplier_id');
    }

    public function supplier()
    {
        return $this->hasMany(Order::class, 'supplier_id');
    }

    public function order_document()
    {
        return $this->hasMany(OrderDocument::class, 'supplier_id');
    }
}
