<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentType extends Model
{
    use HasFactory;
    protected $fillable = ['slug', 'name', 'document_type_category_id', 'is_active', 'modified_by'];

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

    public function supplier_document()
    {
        return $this->belongsTo(SupplierDocument::class, 'document_type_id');
    }

    public function document_type_category()
    {
        return $this->belongsTo(DocumentTypeCategory::class, 'document_type_category_id');
    }
}
