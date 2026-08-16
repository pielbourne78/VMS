<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND is an admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // If not an admin, redirect them back to their student dashboard with an error
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}