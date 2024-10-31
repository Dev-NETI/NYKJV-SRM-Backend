<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\MessageSent;
use App\Models\Messages;

class StoreMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event)
    {
        Messages::create([
            'chats_id' => $event->message->session_id,
            'sender_id' => auth()->id(),
            'content' => $event->message->content,
            'created_at' => now(),
        ]);
    }
}
