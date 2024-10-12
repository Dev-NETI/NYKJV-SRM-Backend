<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['slug','name','is_active','modified_by'];

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

    public function department()
    {
        return $this->hasMany(Department::class,'company_id');
    }

    public function user()
    {
        return $this->hasMany(User::class,'company_id');
    }
}
