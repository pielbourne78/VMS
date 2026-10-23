<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ViolationController extends Controller
{
    public static function formatOffenseOrdinal(int $offenseNumber): ?string
    {
        $n = $offenseNumber;

        if ($n <= 0) {
            return null;
        }

        if (in_array($n % 100, [11, 12, 13], true)) {
            return $n . 'th';
        }

        return $n . (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$n % 10]);
    }

    public function index(Request $request)
    {
        if ($this->isStudentSummaryMode($request)) {
            return $this->renderStudentSummaryView();
        }

        $query = $this->buildViolationQuery($request);
        $sortBy = $this->resolveSortColumn($request->input('sort_by'));
        $sortDir = $this->resolveSortDirection($request->input('sort_dir'));

        $violations = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();
        $summary = $this->buildSummary($query);

        return view('admin.violations.index', [
            'mode' => 'violations',
            'violations' => $violations,
            'students' => collect(),
            'courses' => $this->getCourses(),
            'sections' => $this->getSections(),
            'yearLevels' => $this->getYearLevels(),
            'summary' => $summary,
        ]);
    }

    protected function isStudentSummaryMode(Request $request): bool
    {
        return $request->boolean('view_students') || $request->boolean('students_only');
    }

    protected function renderStudentSummaryView()
    {
        $students = \App\Models\User::query()
            ->whereHas('violations')
            ->withCount('violations')
            ->with([
                'violations' => function ($query) {
                    $query->latest('occurred_at');
                },
            ])
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.violations.index', [
            'mode' => 'students',
            'students' => $students,
            'violations' => collect(),
            'courses' => $this->getCourses(),
            'sections' => $this->getSections(),
            'yearLevels' => $this->getYearLevels(),
            'summary' => $this->buildSummary(Violation::query()),
        ]);
    }

    protected function buildViolationQuery(Request $request)
    {
        return Violation::query()
            ->with(['student', 'issuedBy'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->whereHas('student', fn ($studentQuery) =>
                    $studentQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('full_name', 'like', "%{$term}%")
                        ->orWhere('student_id', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $this->resolveStatusFilter((string) $request->string('status')));
            })
            ->when($request->filled('course'), function ($query) use ($request) {
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('course', $request->string('course')));
            })
            ->when($request->filled('section'), function ($query) use ($request) {
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('section', $request->string('section')));
            })
            ->when($request->filled('year_level'), function ($query) use ($request) {
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('year_level', $request->string('year_level')));
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('occurred_at', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('occurred_at', '<=', $request->date('date_to'));
            });
    }

    protected function resolveStatusFilter(string $status): string
    {
        $map = [
            'pending' => 'consequence_applied',
            'resolved' => 'resolved',
            'active' => 'pending',
            'dismissed' => 'dismissed',
        ];

        return $map[$status] ?? $status;
    }

    protected function resolveSortColumn(?string $sortBy): string
    {
        return in_array($sortBy, ['violation_type', 'status', 'occurred_at', 'violation_code'], true)
            ? $sortBy
            : 'occurred_at';
    }

    protected function resolveSortDirection(?string $sortDir): string
    {
        $direction = strtolower((string) $sortDir ?? 'desc');

        return in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';
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
            '101',
            '102',
            '103',
            '104',
            '105',
            '106',
            '201',
            '202',
            '203',
            '204',
            '205',
            '206',
            '301',
            '302',
            '303',
            '304',
            '305',
            '306',
            '401',
            '402',
            '403',
            '404',
            '405',
            '406',
        ];
    }

    private function getYearLevels(): array
    {
        return ['1st Year', '2nd Year', '3rd Year', '4th Year'];
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


    public function recentViolations(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $violations = Violation::query()
            ->with(['student'])
            ->when($search !== '', function ($query) use ($search) {
                $term = '%' . strtolower($search) . '%';

                $query->where(function ($query) use ($term) {
                    $query->whereHas('student', function ($studentQuery) use ($term) {
                        $studentQuery->whereRaw('LOWER(name) LIKE ?', [$term])
                            ->orWhereRaw('LOWER(full_name) LIKE ?', [$term]);
                    })
                        ->orWhereRaw('LOWER(violation_type) LIKE ?', [$term]);
                });
            })
            ->orderByDesc('occurred_at')
            ->take(15)
            ->get();

        return view('admin.recent-violations', [
            'violations' => $violations,
            'search' => $search,
        ]);
    }

    public function report()
    {
        $violations = Violation::query()
            ->with(['student'])
            ->orderByDesc('occurred_at')
            ->get();

        $report = [
            'total_cases' => $violations->count(),
            'active_cases' => $violations->where('status', 'pending')->count(),
            'pending_actions' => $violations->where('status', 'consequence_applied')->count(),
            'resolved_this_week' => $violations->filter(function ($violation) {
                return $violation->status === 'resolved'
                    && $violation->resolved_at
                    && $violation->resolved_at->diffInDays(now()) <= 7;
            })->count(),
            'escalated_cases' => $violations->filter(function ($violation) {
                return $violation->review_level === 'escalated' || !is_null($violation->escalated_at);
            })->count(),
            'major_cases' => $violations->where('offense_category', 'major')->count(),
            'minor_cases' => $violations->where('offense_category', 'minor')->count(),
            'student_response_rate' => $violations->count() > 0
                ? round(($violations->where('status', 'consequence_applied')->count() / $violations->count()) * 100, 1)
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
            $violation->update($this->buildBulkActionPayload($validated, $violation));
        }

        return redirect()->route('admin.violations.index')
            ->with('success', 'Selected cases updated successfully.');
    }

    protected function buildBulkActionPayload(array $validated, Violation $violation): array
    {
        $payload = [
            'last_updated_by' => Auth::id(),
            'case_owner_id' => Auth::id(),
        ];

        if ($validated['action'] === 'resolve') {
            $payload = array_merge($payload, [
                'status' => 'resolved',
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
                'review_level' => 'normal',
                'escalated_at' => null,
                'escalation_reason' => null,
            ]);
        }

        if ($validated['action'] === 'escalate') {
            $payload = array_merge($payload, [
                'status' => 'pending',
                'review_level' => 'escalated',
                'escalated_at' => now(),
                'escalation_reason' => $validated['reason'] ?? 'Escalated for review by administrator.',
            ]);
        }

        if ($validated['action'] === 'clear_escalation') {
            $payload = array_merge($payload, [
                'review_level' => 'normal',
                'escalated_at' => null,
                'escalation_reason' => null,
            ]);
        }

        return $payload;
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
        $validated = $this->validateViolationInput($request);

        Violation::create($this->buildViolationPayload($validated));

        return redirect()
            ->route('admin.violation.monitoring')
            ->with('success', 'Violation recorded successfully!');
    }

    protected function getLocations(): array
    {
        return [
            'Building A',
            'Building B',
            'Canteen',
            'Library',
            'Parking Area',
            'Gymnasium',
            'Classroom',
        ];
    }

    protected function validateViolationInput(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'violation_type' => ['required', 'string', 'max:255'],
            'occurred_at' => ['required', 'date', 'before_or_equal:now'],
            'location' => ['required', 'string', 'max:255', 'in:' . implode(',', $this->getLocations())],
            'description' => ['required', 'string', 'min:5', 'max:1000'],
            'notification_alert' => ['nullable'],
            'student_notification' => ['nullable'],
        ]);
    }

    protected function buildViolationPayload(array $validated): array
    {
        return [
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
        ];
    }

}