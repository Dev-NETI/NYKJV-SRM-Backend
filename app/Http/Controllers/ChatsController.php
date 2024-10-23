<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
     public function index()
     {
         $userId = auth()->id();
         $chats = Chats::where('sender_id', $userId)
             ->with('messages')
             ->get();
         return response()->json($chats);
     }
 
     public function show($id)
     {
         return response()->json(Chats::with('messages')->find($id));
     }
 
    
     public function store(Request $request)
     {
         $chat = Chats::create($request->all());
         return response()->json($chat, 201);
     }
 
     public function destroy($id)
     {
         Chats::destroy($id);
         return response()->json(null, 204);
     }
}
