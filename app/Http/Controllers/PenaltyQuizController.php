<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\StudentQuizSubmissionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenaltyQuizController extends Controller
{
    public function show(Violation $violation): View|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->userDoesNotOwnViolation($violation)) {
            abort(403);
        }

        if (!$this->violationIsAwaitingQuiz($violation)) {
            return redirect()->route('dashboard')->with('error', 'This violation is not currently awaiting a penalty quiz.');
        }

        $quiz = Quiz::ensureForViolation($violation);

        return view('student.penalty-quiz', compact('violation', 'quiz'));
    }

    public function submit(Request $request, Violation $violation): RedirectResponse
    {
        if ($this->userDoesNotOwnViolation($violation)) {
            abort(403);
        }

        $quiz = Quiz::ensureForViolation($violation);
        $questions = $quiz->questions()->get();

        $rules = [];
        foreach ($questions as $question) {
            $rules['question_' . $question->id] = ['required', 'string', 'min:10', 'max:2000'];
        }

        $validated = $request->validate($rules);
        $answers = $this->extractSubmissionAnswers($request, $questions, $validated);

        $score = $this->calculateSubmissionScore($questions, $answers);
        $totalQuestions = $questions->count();
        $percentage = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0;
        $passed = $totalQuestions > 0 && collect($answers)->every(fn ($answer) => trim((string) $answer) !== '');

        $quiz->attempts()->create([
            'user_id' => auth()->id(),
            'answers' => $answers,
            'score' => (int) round($percentage),
            'total_questions' => $totalQuestions,
            'passed' => $passed,
        ]);

        $this->notifyAdminsOfSubmission();
        $violation->update(['status' => 'consequence_applied']);

        return redirect()->route('report')->with('success', 'Your reasoning has been submitted. The admin will review it and update the case status.');
    }

    protected function userDoesNotOwnViolation(Violation $violation): bool
    {
        return auth()->id() !== $violation->user_id;
    }

    protected function violationIsAwaitingQuiz(Violation $violation): bool
    {
        return in_array($violation->status, ['pending', 'consequence_applied'], true);
    }

    protected function extractSubmissionAnswers(Request $request, $questions, array $validated = []): array
    {
        return $questions->mapWithKeys(function ($question) use ($request, $validated) {
            $key = 'question_' . $question->id;
            $value = trim((string) ($validated[$key] ?? $request->input($key, '')));

            return [$question->id => $value];
        })->all();
    }

    protected function calculateSubmissionScore($questions, array $answers): int
    {
        return collect($questions)
            ->filter(fn ($question) => trim((string) ($answers[$question->id] ?? '')) !== '')
            ->sum(fn ($question) => (int) $question->points);
    }

    protected function notifyAdminsOfSubmission(): void
    {
        /** @var iterable<User> $admins */
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            if (!$admin instanceof User) {
                continue;
            }

            $admin->notify(new StudentQuizSubmissionNotification([
                'title' => 'Quiz submission received',
                'message' => auth()->user()->full_name . ' submitted a penalty quiz for review.',
                'url' => route('admin.appeals'),
                'type' => 'student_quiz_submission',
            ]));
        }
    }
}
