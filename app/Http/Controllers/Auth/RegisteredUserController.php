<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $fullName = $request->input('full_name', $request->input('name'));

        $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'student_id' => ['nullable', 'string', 'max:20', 'unique:users'],
            'year_level' => ['nullable', 'string', 'max:10'],
            'section' => ['nullable', 'string', 'max:20'],
            'course' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $fullName ?? $request->email,
            'full_name' => $fullName ?? $request->email,
            'email' => $request->email,
            'student_id' => $request->student_id ?? null,
            'year_level' => $request->year_level ?? null,
            'section' => $request->section ?? null,
            'course' => $request->course ?? null,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(AppServiceProvider::HOME);
    }
}
