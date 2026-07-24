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
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'student_id' => ['required', 'string', 'max:20', 'unique:users'],
            'year_level' => ['required', 'string', 'max:10'],
            'section' => ['required', 'string', 'max:20'],
            'course' => ['required', 'string', 'max:255'],
           // ---  THE PASSWORD RULES HERE ---
            'password' => [
            'required', 
            'confirmed', 
            Rules\Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ],
        ]);
    
        $user = User::create([
            'name' => $request->full_name, // Maps your input to the mandatory database column
            'full_name' => $request->full_name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'year_level' => $request->year_level,
            'section' => $request->section,
            'course' => $request->course,
            'password' => Hash::make($request->password),
        ]);
    
        event(new Registered($user));
        Auth::login($user);
    
        return redirect(AppServiceProvider::HOME);
    }
}
