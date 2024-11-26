<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'company',
        'contact_person',
        'contact_number',
        'email_address',
        'address',
        'products',
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

}
