<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'chats_id',
        'sender_id',
        'content',
        'unread',
        'attachment_file_name',
        'attachment_type',
        'attachment_size',
    ];
    protected $with = ['sender'];

    public function chat()
    {
        return $this->belongsTo(Chats::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
