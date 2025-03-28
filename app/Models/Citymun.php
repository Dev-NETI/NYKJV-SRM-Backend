<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citymun extends Model
{
    //
    protected $table = 'refcitymun';
    protected $fillable = [
        'id',
        'psgcCode',
        'citymunDesc',
        'regDesc',
        'provCode',
        'citymunCode'
    ];
}
