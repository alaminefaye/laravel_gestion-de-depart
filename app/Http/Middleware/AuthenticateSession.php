<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthenticateSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $currentSessionId = Session::getId();

        if (!config('auth.allow_multiple_sessions') && $user->hasMultipleSessions()) {
            // Si les sessions multiples ne sont pas autorisées, on garde uniquement la session courante
            foreach ($user->sessions() as $session) {
                if ($session->id !== $currentSessionId) {
                    Session::getHandler()->destroy($session->id);
                }
            }
        }

        // Mettre à jour les informations de la session
        Session::put('last_activity', time());
        Session::put('ip_address', $request->ip());
        Session::put('user_agent', $request->userAgent());

        return $next($request);
    }
}
