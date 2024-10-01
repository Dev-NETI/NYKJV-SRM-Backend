<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Symfony\Component\HttpFoundation\Response;

class EnsureOTPVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $otp_verified = FacadesSession::get('otp_verified');
        // $otp_verified = $request->session()->get('otp_verified');
        if ($request->user() && $otp_verified) {
            return $next($request);
        }
    }
}
