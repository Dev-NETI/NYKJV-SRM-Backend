<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'modified_by',
        'image_path',
        'currency_id',
        'price_vat_ex',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
