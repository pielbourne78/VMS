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
    public function index(): View
    {
        $attempts = QuizAttempt::with(['user', 'quiz.violation.student'])
            ->whereHas('quiz.violation', function ($query) {
                $query->where('status', 'consequence_applied');
            })
            ->latest('created_at')
            ->get();

        return view('admin.appeals', compact('attempts'));
    }

    public function review(Request $request, QuizAttempt $quizAttempt): RedirectResponse
    {
        $approved = $request->boolean('approved');

        $quizAttempt->update([
            'admin_approved' => $approved,
            'admin_note' => $request->input('admin_note'),
            'reviewed_at' => now(),
        ]);

        $violation = $quizAttempt->quiz->violation;
        $violation->update([
            'status' => $approved ? 'resolved' : 'consequence_applied',
        ]);

        if ($quizAttempt->user) {
            $quizAttempt->user->notify(new ViolationStatusUpdated([
                'title' => $approved ? 'Reasoning Approved' : 'Reasoning Review Pending',
                'message' => $approved
                    ? 'Your reasoning was approved and your violation case has been resolved.'
                    : 'Your reasoning was not approved. The case remains active for further review.',
                'url' => route('report'),
                'type' => 'appeal_decision',
            ]));
        }

        return redirect()->route('admin.appeals')->with(
            'success',
            $approved ? 'The student reasoning was approved and the violation has been resolved.' : 'The student reasoning was not approved. The case remains active under consequence review.'
        );
    }
}
