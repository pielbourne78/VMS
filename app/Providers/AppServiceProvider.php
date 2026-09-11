<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    RedirectIfAuthenticated::redirectUsing(function ($request) {
        $user = Auth::user();

        if (!$user) {
            return route('dashboard');
        }

        // If admin tries to open Student Login → logout first, then show login form
        if ($request->routeIs('login') && $user->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return route('login');
        }

        // If student tries to open Admin Login → logout first
        if ($request->routeIs('admin.login') && !$user->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return route('admin.login');
        }

        // Normal redirect
        return $user->is_admin
            ? route('admin.dashboard')
            : route('dashboard');
    });
}

    public const HOME = '/dashboard';
}