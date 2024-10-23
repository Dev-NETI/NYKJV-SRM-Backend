<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;

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
         $message = Messages::create($request->all());
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
}
