<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
   protected $with = ['messages', 'participants'];

    // Relationship: A chat can have many messages.
    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    // Relationship: A chat can have multiple participants
    public function participants()
    {
        return $this->hasMany(ChatParticipants::class, 'chat_id');
    }
}
