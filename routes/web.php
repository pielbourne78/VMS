<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        Mail::raw('Testing connection', function ($message) {
            $message->to('YOUR_EMAIL@example.com')->subject('Test Email');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::post('/validate-fields', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'student_id' => 'required|string|unique:users,student_id',
        'password' => [
            'required',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ],
    ], [
        'password.min' => 'Password must be at least 8 characters long.',
        'password.mixedCase' => 'Password must include uppercase and lowercase letters.',
        'password.numbers' => 'Password must include at least one number.',
        'password.symbols' => 'Password must include at least one special character.',
        'password.confirmed' => 'Password confirmation does not match.',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    return response()->json(['message' => 'Valid']);
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';