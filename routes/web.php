<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController as AdminReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentViolationController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\RecordViolationController;
use App\Http\Controllers\ConsequenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


Route::get('/violation-monitoring', [StudentViolationController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('violation.monitoring');

//Admin-exclusive Violation Tracking & Monitoring Routes
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.violations.')->group(function () {
    Route::get('/violations', [ViolationController::class, 'index'])->name('index');
    Route::post('/violations', [ViolationController::class, 'store'])->name('store');
    Route::get('/violations/student/{user}', [ViolationController::class, 'history'])->name('history');
    Route::patch('/violations/{violation}', [ViolationController::class, 'update'])->name('update');
    Route::delete('/violations/{violation}', [ViolationController::class, 'destroy'])->name('destroy');
});

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
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    $violations = \App\Models\Violation::where('user_id', auth()->id())
        ->orderByDesc('occurred_at')
        ->get();

    return view('dashboard', compact('violations'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/violations/{violation}/penalty-quiz', [\App\Http\Controllers\PenaltyQuizController::class, 'show'])
        ->name('student.penalty.quiz');

    Route::post('/violations/{violation}/penalty-quiz', [\App\Http\Controllers\PenaltyQuizController::class, 'submit'])
        ->name('student.penalty.quiz.submit');
});
Route::get('/report', [StudentViolationController::class, 'report'])
    ->middleware(['auth', 'verified'])
    ->name('report');

Route::get('/user-photo/{path}', function (string $path) {
    $fullPath = Storage::disk('public')->path($path);

    abort_unless(Storage::disk('public')->exists($path) && is_file($fullPath), 404);

    return response()->file($fullPath);
})->where('path', '.*')->name('user.photo');

Route::view('/code-of-discipline', 'code-of-discipline')
    ->middleware(['auth', 'verified'])
    ->name('code.of.discipline');

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/report', [ViolationController::class, 'report'])->name('report');
    Route::get('/violations/report', [ViolationController::class, 'report'])->name('violations.report');

    // Record Violation
    Route::get('/violation-monitoring', [RecordViolationController::class, 'create'])->name('violation.monitoring');
    Route::post('/record-violations', [RecordViolationController::class, 'store'])->name('violations.record.store');

    // Violation Monitoring Overview (Recent Violations list)
    Route::get('/violations/recent', [ViolationController::class, 'recentViolations'])->name('violations.recent');

    // Apply Consequences
    Route::get('/consequences', [ConsequenceController::class, 'index'])->name('consequences');
    Route::patch('/violations/{violation}/approve', [ConsequenceController::class, 'approve'])->name('violations.approve');

    // Student reasoning appeals review
    Route::get('/appeals', [\App\Http\Controllers\AdminAppealController::class, 'index'])->name('appeals');
    Route::patch('/appeals/{quizAttempt}/review', [\App\Http\Controllers\AdminAppealController::class, 'review'])->name('appeals.review');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Profile Picture Routes
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'destroyProfilePicture'])->name('profile.picture.destroy');

    Route::post('/notifications/{notification}/read', function ($notification) {
        $user = auth()->user();
        $item = $user->notifications()->findOrFail($notification);

        if (is_null($item->read_at)) {
            $item->markAsRead();
        }

        return response()->json(['status' => 'read', 'notification_id' => $notification]);
    })->name('notifications.read');
});

require __DIR__ . '/auth.php';