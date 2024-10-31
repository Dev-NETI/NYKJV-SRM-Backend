<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatParticipants extends Model
{
    protected $fillable = ['chat_id', 'sender'];
    protected $with = ['sender'];
    public function chat()
    {
        return $this->belongsTo(Chats::class, 'chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
