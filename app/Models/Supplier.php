<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['slug','name','island_id','province_id','municipality_id',
    'brgy_id','street_address','is_active','modified_by'];

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
        $this->hasMany(User::class,'supplier_id');
    }

    public function department_supplier()
    {
        return $this->hasMany(DepartmentSupplier::class,'supplier_id');
    }

}
