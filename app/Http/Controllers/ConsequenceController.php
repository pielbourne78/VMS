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
    public function approve(Request $request, Violation $violation): RedirectResponse
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($violation->status !== 'pending') {
            return back()->withErrors([
                'status' => 'Only open pending violations can be approved for consequence action.',
            ]);
        }

        $violation->update(['status' => 'consequence_applied']);

        if ($violation->student) {
            $sanctionLabel = $violation->sanction_label ?: ($violation->offense_code ?: 'Sanction charged');

            $violation->student->notify(new ViolationStatusUpdated([
                'title' => 'Sanction Warning',
                'message' => 'Sanction Warning: sanctions charged — ' . $sanctionLabel . '. This case involves a violation committed by the student. Complete the penalty quiz to resolve the case.',
                'url' => route('student.penalty.quiz', $violation),
                'type' => 'sanction_warning',
                'severity' => 'warning',
                'sanction' => $sanctionLabel,
                'violation_id' => $violation->id,
                'case_code' => $violation->violation_code ?? $violation->offense_code ?? 'N/A',
            ]));

            $violation->student->notify(new ViolationStatusUpdated([
                'title' => 'Consequence Applied',
                'message' => 'A consequence was applied to your case for ' . $violation->violation_type . '. Your case is now pending. Please complete the penalty quiz.',
                'url' => route('student.penalty.quiz', $violation),
                'type' => 'consequence_applied',
                'severity' => 'info',
                'sanction' => $sanctionLabel,
                'violation_id' => $violation->id,
                'case_code' => $violation->violation_code ?? $violation->offense_code ?? 'N/A',
            ]));
        }

        return redirect()
            ->route('admin.consequences')
            ->with('success', 'Consequence applied. A sanction warning has been issued. The student must complete the penalty quiz to resolve this case.');
    }
}