<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempQuotation extends Model
{
    protected $fillable = ['slug', 'file_path', 'is_active', 'created_by'];

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