<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViolationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

// Route::get('/apply-consequences', [ViolationController::class, 'index'])
//     ->middleware(['auth']); // I-check kung aling middleware ang nakaharang

Route::middleware(['auth', 'admin'])->group(function () {
    // Apply Consequences Page
    Route::get('/apply-consequences', [ViolationController::class, 'applyConsequences'])->name('admin.apply-consequences');
    
    // Approve Consequence Action
    Route::patch('/violations/{id}/approve', [ViolationController::class, 'approve'])->name('admin.violations.approve');
});


Route::get('/violation-monitoring', [ViolationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('violation.monitoring');

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

Route::get('/report', function () {
    return view('report');
})->middleware(['auth', 'verified'])->name('report');

Route::get('/user-photo/{path}', function (string $path) {
    $fullPath = Storage::disk('public')->path($path);

    abort_unless(Storage::disk('public')->exists($path) && is_file($fullPath), 404);

    return response()->file($fullPath);
})->where('path', '.*')->name('user.photo');

Route::view('/code-of-discipline', 'code-of-discipline')
    ->middleware(['auth', 'verified'])
    ->name('code.of.discipline');

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Profile Picture Routes
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'destroyProfilePicture'])->name('profile.picture.destroy');
});

require __DIR__ . '/auth.php';