<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\MessageSent;
use App\Models\Messages;
use Illuminate\Support\Facades\Log;

class StoreMessage implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        

    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event)
    {  
        
        Log::info($event->message);
        // Messages::create([
        //     'chats_id' => $event->message->chats_id,
        //     'sender_id' => $event->message->sender_id,
        //     'content' => $event->message->content,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        //     'unread' => true
        // ]);
    }
}
