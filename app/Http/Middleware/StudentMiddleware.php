<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && ! auth()->user()->is_admin) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard')->with('error', 'This student section is not available for admin accounts.');
        }

        return redirect()->route('login');
    }
}
