<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::query()
            ->with(['student', 'issuedBy'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search');
                $q->whereHas('student', fn($s) =>
                    $s->where('name', 'like', "%{$term}%")
                        ->orWhere('full_name', 'like', "%{$term}%")
                        ->orWhere('student_id', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn($q) =>
                $q->where('status', $request->string('status')))
            ->when($request->filled('course'), function ($q) use ($request) {
                $q->whereHas('student', fn($studentQuery) =>
                    $studentQuery->where('course', $request->string('course')));
            })
            ->when($request->filled('section'), function ($q) use ($request) {
                $q->whereHas('student', fn($studentQuery) =>
                    $studentQuery->where('section', $request->string('section')));
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('occurred_at', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('occurred_at', '<=', $request->date('date_to'));
            });

        $sortBy = in_array($request->input('sort_by'), ['violation_type', 'status', 'occurred_at', 'violation_code'], true)
            ? $request->input('sort_by')
            : 'occurred_at';

        $sortDir = in_array(strtolower((string) $request->input('sort_dir', 'desc')), ['asc', 'desc'], true)
            ? strtolower((string) $request->input('sort_dir', 'desc'))
            : 'desc';

        $violations = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(15)
            ->withQueryString();

        $courses = User::query()
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course');

        $sections = User::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view('admin.violations.index', compact('violations', 'courses', 'sections'));
    }

    public function history(Request $request, User $user)
    {
        $violations = $user->violations()
            ->with(['issuedBy'])
            ->orderByDesc('occurred_at')
            ->paginate(15);

        return view('admin.violations.history', [
            'student' => $user,
            'violations' => $violations,
        ]);
    }

    // ========== NEW METHODS ==========

    public function create()
    {
        $students = User::where('role', 'student')
            ->orderBy('student_id')
            ->get();

        $violationTypes = [
            'Late',
            'Uniform Violation',
            'Misconduct',
            'Absence',
            'Disrespect',
            'Others'
        ];

        $locations = [
            'Building A',
            'Building B',
            'Canteen',
            'Library',
            'Parking Area',
            'Gymnasium',
            'Classroom'
        ];

        return view('admin.violation-monitoring', compact('students', 'violationTypes', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'violation_type'       => 'required|string',
            'occurred_at'          => 'required|date',
            'location'             => 'required|string',
            'description'          => 'required|string',
            'notification_alert'   => 'nullable',
            'student_notification' => 'nullable',
        ]);

        Violation::create([
            'user_id'               => $validated['user_id'],
            'violation_type'        => $validated['violation_type'],
            'occurred_at'           => $validated['occurred_at'],
            'location'              => $validated['location'],
            'description'           => $validated['description'],
            'reported_by'           => Auth::id(),
            'notification_alert'    => $request->boolean('notification_alert'),
            'student_notification'  => $request->boolean('student_notification'),
            'status'                => 'pending', // default status
        ]);

        return redirect()
            ->route('admin.violation.monitoring')
            ->with('success', 'Violation recorded successfully!');
    }
}