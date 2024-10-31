<?php

namespace App\Http\Controllers;

use App\Models\ChatParticipants;
use App\Models\Chats;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatsController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        //get chats where user is a participant
        $chats = Chats::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return response()->json($chats);
    }

    public function show($id)
    {
        return response()->json(Chats::with('messages')->find($id));
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'sender_id' => 'required|exists:users,id',
        ]);

        // Create a new chat within a transaction
        $chat = DB::transaction(function () use ($request) {
            $chat = Chats::create();

            // Add Chat Participants
            ChatParticipants::insert([
                [
                    'chat_id' => $chat->id,
                    'user_id' => $request->sender_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'chat_id' => $chat->id,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);



            // Add initial message
            Messages::insert([
                'chats_id' => $chat->id,
                'sender_id' => auth()->id(),
                'content' => 'Welcome to the chat!',
                'created_at' => now(),
            ]);

            // Load relationships
            $chat->load(['participants', 'messages']);

            return $chat;
        });

        return response()->json($chat, 201);
    }

    public function destroy($id)
    {
        Chats::destroy($id);
        return response()->json(null, 204);
    }

    public function join(Request $request, $chatId)
    {
        return response()->json([
            'auth' => [
                'user_id' => auth()->id(),
                'user_info' => auth()->user(),
                'chat_id' => $chatId
            ]
        ]);
    }
}
