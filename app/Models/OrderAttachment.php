<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderAttachment extends Model
{
    protected $fillable = ['slug', 'reference_number', 'name', 'file_path', 'is_active', 'modified_by'];
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

    public function order_attachment()
    {
        return $this->belongsTo(Order::class, 'reference_number', 'reference_number');
    }
}
