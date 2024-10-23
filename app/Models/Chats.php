<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $fillable = [
        'sender_id',
    ];

    // Relationship: A chat can have many messages.
    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    // Relationship: A chat can have multiple participants
    public function participants()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
}
