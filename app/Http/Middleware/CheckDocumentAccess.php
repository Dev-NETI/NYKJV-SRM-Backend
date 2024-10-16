<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDocumentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        // Check if the user has a role that allows category access
        $hasAccess = $user->roles->contains(function ($role) {
            return in_array($role->name, ['Supplier Documen']);
        });

        if (!$hasAccess) {
            return response()->json([
                'message' => 'You do not have permission to access document data.',
            ], 403);
        }

        return $next($request);
    }
}
