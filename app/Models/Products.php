<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 
        'supplier_id', 
        'category_id', 
        'brand_id', 
        'name', 
        'price', 
        'specification', 
        'is_active', 
        'modified_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = encrypt(static::count() + 1); // Generate slug based on count
            $model->modified_by = 'system'; // Set default modifier
        });

        static::updating(function ($model) {
            $model->modified_by = 'system'; // Set default modifier on update
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
