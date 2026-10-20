<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ViolationController extends Controller
{
    public function getOffenseOrdinalAttribute(): ?string
{
    $n = $this->offense_number;

    if (!$n) {
        return null;
    }

    if (in_array($n % 100, [11, 12, 13], true)) {
        return $n . 'th';
    }

    return $n . (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$n % 10]);
}
  public function index(Request $request)
{
    // Check if we are in "All" mode (no specific filters)
    $isAllMode = !$request->filled('status')
        && !$request->filled('course')
        && !$request->filled('section')
        && !$request->filled('year_level')
        && !$request->filled('date_from')
        && !$request->filled('date_to')
        && !$request->filled('search');

    if ($isAllMode) {
        // ===== SHOW ONE ROW PER STUDENT =====
        $students = \App\Models\User::query()
            ->whereHas('violations')
            ->withCount('violations')
            ->with(['violations' => function ($q) {
                $q->latest('occurred_at');
            }])
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        $courses = $this->getCourses();
        $sections = $this->getSections();
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        $summary = $this->buildSummary(Violation::query());

        return view('admin.violations.index', [
            'mode' => 'students',
            'students' => $students,
            'violations' => collect(), // empty
            'courses' => $courses,
            'sections' => $sections,
            'yearLevels' => $yearLevels,
            'summary' => $summary,
        ]);
    }

    // ===== NORMAL MODE (individual violations) =====
    $query = Violation::query()
        ->with(['student', 'issuedBy'])
        ->when($request->filled('search'), function ($q) use ($request) {
            $term = $request->string('search');
            $q->whereHas('student', fn($s) =>
                $s->where('name', 'like', "%{$term}%")
                    ->orWhere('full_name', 'like', "%{$term}%")
                    ->orWhere('student_id', 'like', "%{$term}%"));
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            $status = (string) $request->string('status');
            $statusMap = [
                'pending'   => 'consequence_applied',
                'resolved'  => 'resolved',
                'active'    => 'pending',
                'dismissed' => 'dismissed',
            ];
            $dbStatus = $statusMap[$status] ?? $status;
            $q->where('status', $dbStatus);
        })
        ->when($request->filled('course'), function ($q) use ($request) {
            $q->whereHas('student', fn($s) => $s->where('course', $request->string('course')));
        })
        ->when($request->filled('section'), function ($q) use ($request) {
            $q->whereHas('student', fn($s) => $s->where('section', $request->string('section')));
        })
        ->when($request->filled('year_level'), function ($q) use ($request) {
            $q->whereHas('student', fn($s) => $s->where('year_level', $request->string('year_level')));
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

    $violations = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();
    $summary = $this->buildSummary($query);

    return view('admin.violations.index', [
        'mode' => 'violations',
        'violations' => $violations,
        'students' => collect(),
        'courses' => $this->getCourses(),
        'sections' => $this->getSections(),
        'yearLevels' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
        'summary' => $summary,
    ]);
}

    protected function buildSummary($query): array
    {
        $escalatedCount = Schema::hasColumn('violations', 'review_level')
            ? (clone $query)->where('review_level', 'escalated')->count()
            : (clone $query)->whereNotNull('escalated_at')->count();

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'pending')->count(),
            'pending' => (clone $query)->where('status', 'consequence_applied')->count(),
            'resolved' => (clone $query)->where('status', 'resolved')->count(),
            'dismissed' => (clone $query)->where('status', 'dismissed')->count(),
            'major' => (clone $query)->where('offense_category', 'major')->count(),
            'minor' => (clone $query)->where('offense_category', 'minor')->count(),
            'escalated' => $escalatedCount,
        ];
    }

// Helper methods (add these inside the controller class)
private function getCourses()
{
    return [
        "BS Business Administration - Human Resources Dev't Mgt.",
        "BS Business Administration - Marketing Management",
        "BS Entrepreneurship",
        "BS Accountancy",
        "BS Information Technology",
        "BS Secondary Education - English",
        "BS Secondary Education - Mathematics",
        "BS Secondary Education - PE",
        "BS Elementary Education - SPEd",
        "Computer Hardware Servicing NCII",
    ];
}

private function getSections()
{
    return [
        '101','102','103','104','105','106',
        '201','202','203','204','205','206',
        '301','302','303','304','305','306',
        '401','402','403','404','405','406',
    ];
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


    public function recentViolations()
    {
        $violations = Violation::query()
            ->with(['student'])
            ->orderByDesc('occurred_at')
            ->take(15)
            ->get();

        return view('admin.recent-violations', compact('violations'));
    }

    public function report()
    {
        $violations = Violation::query()
            ->with(['student'])
            ->orderByDesc('occurred_at')
            ->get();

        $activeCases = $violations->where('status', 'pending');
        $pendingCases = $violations->where('status', 'consequence_applied');
        $resolvedThisWeek = $violations->filter(fn ($violation) => $violation->status === 'resolved'
            && $violation->resolved_at && $violation->resolved_at->diffInDays(now()) <= 7);
        $escalatedCases = $violations->filter(fn ($violation) => $violation->review_level === 'escalated' || !is_null($violation->escalated_at));

        $report = [
            'total_cases' => $violations->count(),
            'active_cases' => $activeCases->count(),
            'pending_actions' => $pendingCases->count(),
            'resolved_this_week' => $resolvedThisWeek->count(),
            'escalated_cases' => $escalatedCases->count(),
            'major_cases' => $violations->where('offense_category', 'major')->count(),
            'minor_cases' => $violations->where('offense_category', 'minor')->count(),
            'student_response_rate' => $violations->count() > 0
                ? round(($pendingCases->count() / $violations->count()) * 100, 1)
                : 0,
            'resolution_window' => $violations->whereNotNull('resolved_at')->count() > 0
                ? round($violations->whereNotNull('resolved_at')->avg(fn ($violation) => $violation->resolved_at->diffInDays($violation->occurred_at)), 1)
                : 0,
        ];

        return view('admin.report', [
            'violations' => $violations,
            'report' => $report,
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'violations' => ['required', 'array'],
            'violations.*' => ['integer', 'exists:violations,id'],
            'action' => ['required', 'in:resolve,escalate,clear_escalation'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $violations = Violation::whereIn('id', $validated['violations'])->get();

        foreach ($violations as $violation) {
            $payload = [
                'last_updated_by' => Auth::id(),
                'case_owner_id' => Auth::id(),
            ];

            if ($validated['action'] === 'resolve') {
                $payload['status'] = 'resolved';
                $payload['resolved_by'] = Auth::id();
                $payload['resolved_at'] = now();
                $payload['review_level'] = 'normal';
                $payload['escalated_at'] = null;
                $payload['escalation_reason'] = null;
            }

            if ($validated['action'] === 'escalate') {
                $payload['status'] = 'pending';
                $payload['review_level'] = 'escalated';
                $payload['escalated_at'] = now();
                $payload['escalation_reason'] = $validated['reason'] ?? 'Escalated for review by administrator.';
            }

            if ($validated['action'] === 'clear_escalation') {
                $payload['review_level'] = 'normal';
                $payload['escalated_at'] = null;
                $payload['escalation_reason'] = null;
            }

            $violation->update($payload);
        }

        return redirect()->route('admin.violations.index')
            ->with('success', 'Selected cases updated successfully.');
    }

    public function escalate(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $violation->update([
            'case_owner_id' => Auth::id(),
            'last_updated_by' => Auth::id(),
            'review_level' => 'escalated',
            'escalated_at' => now(),
            'escalation_reason' => $validated['reason'] ?? 'Escalated for admin review.',
            'status' => 'pending',
        ]);

        return redirect()->route('admin.violations.index')
            ->with('success', 'Case escalated to the review queue.');
    }

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
            'user_id' => 'required|exists:users,id',
            'violation_type' => 'required|string',
            'occurred_at' => 'required|date',
            'location' => 'required|string',
            'description' => 'required|string',
            'notification_alert' => 'nullable',
            'student_notification' => 'nullable',
        ]);

        Violation::create([
            'violation_code' => 'V-' . strtoupper(uniqid()),
            'user_id' => $validated['user_id'],
            'issued_by' => Auth::id(),
            'recorded_by' => Auth::id(),
            'case_owner_id' => Auth::id(),
            'last_updated_by' => Auth::id(),
            'violation_type' => $validated['violation_type'],
            'description' => $validated['location'] . ' — ' . $validated['description'],
            'occurred_at' => $validated['occurred_at'],
            'status' => 'pending',
            'review_level' => 'normal',
        ]);

        return redirect()
            ->route('admin.violation.monitoring')
            ->with('success', 'Violation recorded successfully!');
    }

}