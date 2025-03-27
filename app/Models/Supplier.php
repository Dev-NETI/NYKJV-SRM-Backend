<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'slug',
        'name',
        'department',
        'region',
        'province',
        'citymun',
        'brgy',
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
            $model->modified_by = 'system';
        });

        static::updating(function ($model) {
            $model->modified_by = 'system';
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

    public function department(){
        return $this->hasOne(Department::class, 'id', 'department');
    }
    
    public function region()
    {
        return $this->hasOne(Region::class, 'regCode', 'region');
    }
    public function province()
    {
        return $this->hasOne(Province::class, 'provCode', 'province');
    }

    public function citymun()
    {
        return $this->hasOne(Citymun::class, 'citymunCode', 'citymun');
    }

    public function brgy(){
        return $this->hasOne(Barangay::class, 'brgyCode', 'brgy');
    }
}