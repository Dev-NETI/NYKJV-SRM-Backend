<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SupplierDocument extends Model
{
    use HasFactory;
    protected $fillable = ['slug','supplier_id','document_type_id','name','file_path',
    'expired_at','is_active','modified_by'];

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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class,'document_type_id');
    }

}
