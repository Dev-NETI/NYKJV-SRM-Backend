<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    //
    protected $table = 'refbrgy';
    protected $fillable = [
        'id',
        'brgyCode',
        'brgyDesc',
        'regCode',
        'provCode',
        'citymunCode'
    ];
}
