<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;

use App\Models\Chats;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
    // List all messages for a specific chat 
    public function index($chatId)
    {
        return response()->json(Messages::where('chat_id', $chatId)->get());
    }

    // Show a specific message
    public function show($id)
    {
        return response()->json(Messages::find($id));
    }

    // Store a new message
    public function store(Request $request)
    {
        //vallidate the request
        $request->validate([
            'content' => 'required|string',

        ]);

        $chat = Chats::find($request->chats_id);
        if (!$chat->participants()->where('user_id', auth()->id())->exists()) {
            return response()->json(['error' => 'You are not part of this chat.'], 403);
        }

        $message = DB::transaction(function () use ($request) {
            $message = Messages::create([
                'content' => $request->content,
                'chats_id' => $request->chats_id,
                'sender_id' => Auth::id(),
                'unread' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new MessageSent($message));

            return $message;
        });


        return response()->json($message, 201);
    }

    // Update a message
    public function update(Request $request, $id)
    {
        $message = Messages::findOrFail($id);
        $message->update($request->all());
        return response()->json($message);
    }

    // Delete a message
    public function destroy($id)
    {
        Messages::destroy($id);
        return response()->json(null, 204);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $messages = Messages::where('chats_id', $request->chat_id)
            ->where('unread', true)
            ->update(['unread' => false]);

        Log::info("Messages " . $messages);

        return response()->json(['success' => true]);
    }
}
