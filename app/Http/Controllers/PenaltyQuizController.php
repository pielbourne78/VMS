<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Violation;
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

        if (auth()->id() !== $violation->user_id) {
            abort(403);
        }

        if (!in_array($violation->status, ['consequence_applied'], true)) {
            return redirect()->route('dashboard')->with('error', 'This violation is not currently awaiting a penalty quiz.');
        }

        $quiz = Quiz::ensureForViolation($violation);

        return view('student.penalty-quiz', compact('violation', 'quiz'));
    }

    public function submit(Request $request, Violation $violation): RedirectResponse
    {
        if (auth()->id() !== $violation->user_id) {
            abort(403);
        }

        $quiz = Quiz::ensureForViolation($violation);
        $questions = $quiz->questions()->get();

        $score = 0;
        $answers = [];

        foreach ($questions as $question) {
            $key = 'question_' . $question->id;
            $selected = trim((string) ($request->input($key) ?? ''));
            $answers[$question->id] = $selected;

            if ($selected !== '') {
                $score += (int) $question->points;
            }
        }

        $totalQuestions = $questions->count();
        $percentage = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0;
        $passed = $totalQuestions > 0 && collect($answers)->every(fn($answer) => trim((string) $answer) !== '');

        $quiz->attempts()->create([
            'user_id' => auth()->id(),
            'answers' => $answers,
            'score' => (int) round($percentage),
            'total_questions' => $totalQuestions,
            'passed' => $passed,
        ]);

        $violation->update(['status' => 'consequence_applied']);

        return redirect()->route('report')->with('success', 'Your reasoning has been submitted. The admin will review it and update the case status.');
    }
}
