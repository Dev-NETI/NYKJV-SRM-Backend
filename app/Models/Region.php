<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    protected $table = 'refregion';
    protected $fillable = [
        'id',
        'psgcCode',
        'regDesc',
        'regCode'
    ];
}
