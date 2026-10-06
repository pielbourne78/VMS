<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        if ($request->routeIs(['login', 'logout', 'password.*', 'verification.*', 'register'])) {
            return $next($request);
        }

        $lastActivity = $request->session()->get('last_activity');
        $timeoutMinutes = (int) config('session.lifetime', 120);

        if ($lastActivity && now()->diffInMinutes($lastActivity) > $timeoutMinutes) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Session expired due to inactivity.');
        }

        $request->session()->put('last_activity', now());

        return $next($request);
    }
}
