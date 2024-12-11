<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDocument extends Model
{
    protected $fillable = ['supplier_id', 'order_document_type_id', 'slug', 'file_name', 'file_path', 'is_active', 'modified_by'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latestId = $model::orderBy('id', 'DESC')->first();
            $slug = $latestId != NULL ? encrypt($latestId->id + 1) : encrypt(1);
            $model->slug = $slug;
            $model->modified_by = 'system'; //Auth::user()->full_name
        });

        static::updating(function ($model) {
            $model->modified_by = 'system'; //Auth::user()->full_name
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function order_document_type()
    {
        return $this->belongsTo(OrderDocumentType::class, 'order_document_type_id');
    }
}
