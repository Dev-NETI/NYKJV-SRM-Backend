<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'slug', 
        'supplier_id', 
        'category_id', 
        'brand_id', 
        'product_image', 
        'name', 
        'currency', 
        'price', 
        'specification', 
        'is_active', 
        'modified_by'
=======
        'slug',
        'supplier_id',
        'category_id',
        'brand_id',
        'name',
        'price',
        'specification',
        'is_active',
        'modified_by',
        'image_path',
        'currency_id',
        'price_vat_ex',
>>>>>>> 83c9d469118b9ba162d5f2a6829abb9251de363f
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = encrypt(static::count() + 1); // Generate slug based on count
            $model->modified_by = Auth::user()->full_name;
        });

        static::updating(function ($model) {
            // $model->modified_by = Auth::user()->full_name;
            $model->modified_by = "test";
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
    public function order()
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}
