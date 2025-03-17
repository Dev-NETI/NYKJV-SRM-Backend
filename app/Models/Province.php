<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = 'refprovince';
    protected $fillable = [
        'id',
        'psgcCode',
        'provDesc',
        'regCode',
        'provCode'
    ];
}
