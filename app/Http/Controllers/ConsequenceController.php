<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Notifications\ViolationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ConsequenceController extends Controller
{
    /**
     * Display pending violations awaiting consequence approval.
     */
    public function index()
    {
        $violations = Violation::with('student')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.apply-consequences', compact('violations'));
    }

    /**
     * Approve a violation, marking it as resolved.
     */
    public function approve(Violation $violation): RedirectResponse
    {
        $violation->update(['status' => 'consequence_applied']);

        if ($violation->student) {
            $violation->student->notify(new ViolationStatusUpdated([
                'title' => 'Consequence Applied',
                'message' => 'Your case for ' . $violation->violation_type . ' is now active. Please complete the penalty quiz.',
                'url' => route('student.penalty.quiz', $violation),
                'type' => 'consequence_applied',
            ]));
        }

        return redirect()
            ->route('admin.consequences')
            ->with('success', 'Consequence applied. The student must complete the penalty quiz to resolve this case.');
    }
}