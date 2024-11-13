<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCategoryAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Check if the user has a role that allows category access
        $hasAccess = $user->roles->contains(function ($role) {
            return in_array($role->name, ['Category']);
        });

        if (!$hasAccess) {
            return response()->json([
                'message' => 'You do not have permission to access category data.',
            ], 403);
        }

        return $next($request);
    }
}
