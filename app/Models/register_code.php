<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class register_code extends Model
{
    protected $fillable = ['code', 'email', 'is_used'];
    protected $table = 'register_codes';
}
