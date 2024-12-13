<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDocumentType extends Model
{
    protected $fillable = ['slug', 'name', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latestId = $model::orderBy('id', 'DESC')->first();
            $slug = $latestId != NULL ? encrypt($latestId->id + 1) : encrypt(1);
            $model->slug = $slug;
        });
    }

    public function order_document()
    {
        return $this->hasMany(OrderDocument::class, 'order_document_type_id');
    }
}
