<?php

namespace App\Http\Controllers;

use App\Models\ChatParticipants;
use App\Models\Chats;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ChatsController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        //get chats where user is a participant
        $chats = Chats::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['participants.user', 'messages'])
            ->withCount([
                'messages as unread_count' => function ($query) use ($userId) {
                    $query->where('sender_id', '!=', $userId)
                        ->where('unread', true);
                }
            ])
            ->get();

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

    public function users()
    {
        $currentUser = auth()->user();
        $users = User::where('id', '!=', $currentUser->id)->get()->map(function ($user) {
            $user->name = $user->getFullNameAttribute();
            return $user;
        });
        return response()->json($users);
    }

    public function addParticipant(Request $request, Chats $chat)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if user is already a participant
        if ($chat->participants()->where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'User is already a participant'], 422);
        }

        // Add participant
        $chat->participants()->create([
            'user_id' => $request->user_id
        ]);

        return response()->json(['message' => 'Participant added successfully']);
    }

    public function removeParticipant(Chats $chat, User $user)
    {
        // Don't allow removing the last participant
        if ($chat->participants()->count() <= 1) {
            return response()->json(['message' => 'Cannot remove last participant'], 422);
        }

        $chat->participants()->where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Participant removed successfully']);
    }
}
