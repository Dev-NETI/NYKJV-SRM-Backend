<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = ['slug', 'name', 'is_active', 'modified_by'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latestId = $model::orderBy('id', 'DESC')->first();
            $slug = $latestId != NULL ? encrypt($latestId->id + 1) : encrypt(1);
            $model->slug = $slug;
            $model->modified_by = 'system';//Auth::user()->full_name
        });

        static::updating(function ($model) {
            $model->modified_by = 'system';//Auth::user()->full_name
        });
    }

    public function order()
    {
        return $this->hasMany(Order::class,'order_status_id');
    }
}
