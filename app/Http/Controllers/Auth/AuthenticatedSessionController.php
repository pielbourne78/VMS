<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Make sure this is imported
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $login_as = $request->routeIs('admin.login') ? 'admin' : 'student';

        if ($login_as === 'admin') {
            return view('auth.admin-login', compact('login_as'));
        }

        // Use the root login view for students (resources/views/login.blade.php)
        return view('login', compact('login_as'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // This authenticates, checks rate limits, and increments failures automatically
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // 1. BLOCK ADMINS: If an admin tries to log in via the Student Login page
        if ($request->input('login_as') === 'student' && $user->is_admin) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Administrators must log in using the Admin Login portal.'),
            ]);
        }

        // 2. BLOCK STUDENTS: If a regular student tries to log in via the Admin Login page
        if ($request->input('login_as') === 'admin' && !$user->is_admin) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('This account does not have administrator privileges.'),
            ]);
        }

        // Redirect admins to their dashboard, students to theirs
        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Respect portal requested in the logout form
        if ($request->input('login_as') === 'admin') {
            return redirect()->route('admin.login');
        }

        return redirect()->route('login');
    }
}