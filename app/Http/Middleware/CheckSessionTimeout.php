<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $lastActivity = $request->session()->get('last_activity');
        $timeoutMinutes = 30; // Define your timeout duration

        // Check if last activity exists and if it exceeds the limit
        if ($lastActivity && now()->diffInMinutes($lastActivity) > $timeoutMinutes) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Session expired due to inactivity.');
        }

        $request->session()->put('last_activity', now());
    }

        return $next($request);
}
}
