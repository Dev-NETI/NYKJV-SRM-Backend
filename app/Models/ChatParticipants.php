<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatParticipants extends Model
{
    protected $fillable = ['chat_id', 'user_id'];
    protected $with = ['user'];
    public function chat()
    {
        return $this->belongsTo(Chats::class, 'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
