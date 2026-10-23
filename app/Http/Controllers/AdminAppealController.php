<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\Violation;
use App\Notifications\ViolationStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAppealController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $attempts = $this->buildAppealQuery($search)->get();

        if ($this->shouldUseMockQueue($attempts)) {
            $attempts = $this->mockAppealAttempts();
        }

        $summaryAttempts = $this->buildAppealSummaryQuery()->get();

        if ($summaryAttempts->isEmpty()) {
            $summaryAttempts = $this->mockAppealAttempts();
        }

        $summary = $this->summarizeAppeals($summaryAttempts);

        return view('admin.appeals', compact('attempts', 'search', 'summary'));
    }

    protected function shouldUseMockQueue($attempts): bool
    {
        return $attempts->isEmpty();
    }

    protected function buildAppealSummaryQuery()
    {
        return QuizAttempt::with(['user', 'quiz.violation.student'])
            ->whereHas('quiz.violation', function ($query) {
                $query->whereIn('status', ['consequence_applied', 'pending', 'resolved']);
            });
    }

    protected function summarizeAppeals($attempts): array
    {
        return [
            'total' => $attempts->count(),
            'pending' => $attempts->where('admin_approved', null)->count(),
            'approved' => $attempts->where('admin_approved', true)->count(),
            'disapproved' => $attempts->where('admin_approved', false)->count(),
        ];
    }

    protected function buildAppealQuery(string $search)
    {
        $query = QuizAttempt::with(['user', 'quiz.violation.student'])
            ->whereNull('admin_approved')
            ->whereHas('quiz.violation', function ($query) {
                $query->whereIn('status', ['consequence_applied', 'pending']);
            });

        if ($search === '') {
            return $this->applyDefaultOrdering($query);
        }

        return $this->applySearchFilter($query, $search);
    }

    protected function applyDefaultOrdering($query)
    {
        return $query->orderByDesc('reviewed_at')->orderByDesc('created_at');
    }

    protected function applySearchFilter($query, string $search)
    {
        $term = $this->buildSearchTerm($search);

        return $query->where(function ($query) use ($term) {
            $query->whereHas('user', function ($userQuery) use ($term) {
                $userQuery->where('name', 'like', $term)
                    ->orWhere('full_name', 'like', $term)
                    ->orWhere('student_id', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        })->orderByDesc('reviewed_at')->orderByDesc('created_at');
    }

    protected function buildSearchTerm(string $search): string
    {
        return '%' . $search . '%';
    }

    protected function mockAppealAttempts(?array $overrides = null)
    {
        $records = [
            [
                'id' => 201,
                'quiz_id' => 11,
                'user_id' => 21,
                'answers' => [
                    1 => 'I was not aware the attendance policy had changed and I had already completed the requirement earlier in the semester.',
                    2 => 'My schedule conflict was due to a medical checkup that was approved by the department coordinator.',
                ],
                'score' => 80,
                'total_questions' => 2,
                'passed' => false,
                'admin_approved' => null,
                'admin_note' => null,
                'reviewed_at' => null,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
                'user' => [
                    'id' => 21,
                    'name' => 'Angel Villafranca',
                    'full_name' => 'Angel Villafranca',
                    'student_id' => '2024-0118',
                    'email' => 'angel.villafranca@globalreciprocal.edu',
                ],
                'quiz' => [
                    'violation' => [
                        'violation_type' => 'Unauthorized Absence',
                        'violation_code' => 'V-104',
                        'offense_code' => 'OFF-21',
                        'user_id' => 21,
                    ],
                ],
            ],
            [
                'id' => 202,
                'quiz_id' => 12,
                'user_id' => 22,
                'answers' => [
                    1 => 'The submitted work was completed before the deadline but not uploaded because of a server outage.',
                    2 => 'I immediately informed the professor and submitted the file after the connection was restored.',
                ],
                'score' => 75,
                'total_questions' => 2,
                'passed' => false,
                'admin_approved' => null,
                'admin_note' => null,
                'reviewed_at' => null,
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(8),
                'user' => [
                    'id' => 22,
                    'name' => 'Rebecca Santos',
                    'full_name' => 'Rebecca Santos',
                    'student_id' => '2024-0257',
                    'email' => 'rebecca.santos@globalreciprocal.edu',
                ],
                'quiz' => [
                    'violation' => [
                        'violation_type' => 'Late Submission',
                        'violation_code' => 'V-208',
                        'offense_code' => 'OFF-09',
                        'user_id' => 22,
                    ],
                ],
            ],
            [
                'id' => 203,
                'quiz_id' => 13,
                'user_id' => 23,
                'answers' => [
                    1 => 'The device used to record the activity assignment malfunctioned, so I submitted the explanation within the same day.',
                    2 => 'I followed up with the instructor and provided proof of the technical issue.',
                ],
                'score' => 70,
                'total_questions' => 2,
                'passed' => false,
                'admin_approved' => null,
                'admin_note' => null,
                'reviewed_at' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
                'user' => [
                    'id' => 23,
                    'name' => 'Daniel Cruz',
                    'full_name' => 'Daniel Cruz',
                    'student_id' => '2024-0311',
                    'email' => 'daniel.cruz@globalreciprocal.edu',
                ],
                'quiz' => [
                    'violation' => [
                        'violation_type' => 'Misconduct Reporting',
                        'violation_code' => 'V-116',
                        'offense_code' => 'OFF-14',
                        'user_id' => 23,
                    ],
                ],
            ],
            [
                'id' => 204,
                'quiz_id' => 14,
                'user_id' => 24,
                'answers' => [
                    1 => 'I completed the required remedial activity after the original deadline because of a health concern.',
                    2 => 'The documentation was submitted to the student affairs office and reviewed by the department.',
                ],
                'score' => 90,
                'total_questions' => 2,
                'passed' => true,
                'admin_approved' => true,
                'admin_note' => 'Supporting documents were verified and the appeal is accepted.',
                'reviewed_at' => now()->subHours(5),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(5),
                'user' => [
                    'id' => 24,
                    'name' => 'Mina Lopez',
                    'full_name' => 'Mina Lopez',
                    'student_id' => '2024-0442',
                    'email' => 'mina.lopez@globalreciprocal.edu',
                ],
                'quiz' => [
                    'violation' => [
                        'violation_type' => 'Academic Requirement Delay',
                        'violation_code' => 'V-330',
                        'offense_code' => 'OFF-17',
                        'user_id' => 24,
                    ],
                ],
            ],
        ];

        $overrides = $overrides ?? request()->session()->get('mock_appeal_decisions', []);

        return collect($records)->map(function (array $record) use ($overrides) {
            $attempt = (object) $record;
            $attempt->user = (object) $record['user'];
            $attempt->quiz = (object) $record['quiz'];
            $attempt->quiz->violation = (object) $record['quiz']['violation'];

            $recordId = (int) $attempt->id;

            if (isset($overrides[$recordId])) {
                $override = $overrides[$recordId];
                $attempt->admin_approved = $override['admin_approved'] ?? $attempt->admin_approved;
                $attempt->admin_note = $override['admin_note'] ?? $attempt->admin_note;
                $attempt->reviewed_at = $override['reviewed_at'] ?? $attempt->reviewed_at;
            }

            return $attempt;
        });
    }

    public function approve(Request $request, $quizAttempt): RedirectResponse
    {
        return $this->resolveDecision($request, $quizAttempt, true);
    }

    public function decline(Request $request, $quizAttempt): RedirectResponse
    {
        return $this->resolveDecision($request, $quizAttempt, false);
    }

    public function review(Request $request, $quizAttempt): RedirectResponse
    {
        return $this->resolveDecision($request, $quizAttempt, $request->boolean('approved'));
    }

    protected function resolveDecision(Request $request, $quizAttempt, bool $approved): RedirectResponse
    {
        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $existingAttempt = QuizAttempt::find($quizAttempt);

        if ($existingAttempt) {
            $existingAttempt->update($this->buildDecisionPayload($request, $approved));

            $violation = $existingAttempt->quiz->violation;
            $violation->update([
                'status' => $approved ? 'resolved' : 'pending',
            ]);

            $notificationPayload = $this->buildNotificationPayload($approved);

            if ($existingAttempt->user) {
                $existingAttempt->user->notify(new ViolationStatusUpdated($notificationPayload));
            }

            if ($request->user()) {
                $request->user()->notify(new ViolationStatusUpdated(array_merge($notificationPayload, [
                    'url' => route('admin.appeals'),
                    'type' => 'appeal_decision',
                ])));
            }

            return redirect()->route('admin.appeals')->with(
                'success',
                $approved
                ? 'The student reasoning was approved and the violation has been resolved.'
                : 'The student reasoning was not approved. The case remains active under consequence review.'
            );
        }

        $this->applyMockDecision($request, $quizAttempt, $approved);

        if ($request->user()) {
            $request->user()->notify(new ViolationStatusUpdated([
                'title' => $approved ? 'Reasoning Approved' : 'Reasoning Review Pending',
                'message' => $approved
                    ? 'Your reasoning was approved and your violation case has been resolved.'
                    : 'Your reasoning was not approved. The case remains active for further review.',
                'url' => route('admin.appeals'),
                'type' => 'appeal_decision',
            ]));
        }

        return redirect()->route('admin.appeals')->with(
            'success',
            $approved
            ? 'The student reasoning was approved and the violation has been resolved.'
            : 'The student reasoning was not approved. The case remains active under consequence review.'
        );
    }

    protected function applyMockDecision(Request $request, $quizAttempt, bool $approved): void
    {
        $decisions = $request->session()->get('mock_appeal_decisions', []);

        $decisions[(int) $quizAttempt] = [
            'admin_approved' => $approved,
            'admin_note' => $request->input('admin_note'),
            'reviewed_at' => now(),
        ];

        $request->session()->put('mock_appeal_decisions', $decisions);
    }

    protected function buildDecisionPayload(Request $request, bool $approved): array
    {
        return [
            'admin_approved' => $approved,
            'admin_note' => $request->input('admin_note'),
            'reviewed_at' => now(),
        ];
    }

    protected function buildNotificationPayload(bool $approved): array
    {
        return [
            'title' => $approved ? 'Reasoning Approved' : 'Reasoning Review Pending',
            'message' => $approved
                ? 'Your reasoning was approved and your violation case has been resolved.'
                : 'Your reasoning was not approved. The case remains active for further review.',
            'url' => route('report'),
            'type' => 'appeal_decision',
        ];
    }
}
