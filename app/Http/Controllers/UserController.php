<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
   

    public function index()
    {
        $currentUser = auth()->user();
        $users = User::where('id', '!=', $currentUser->id)->get()->map(function ($user) {
            $user->name = $user->getFullNameAttribute();
            return $user;
        });
        return response()->json($users);
    }
}
