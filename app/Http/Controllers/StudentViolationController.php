<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentViolationController extends Controller
{
    public function index(Request $request)
{
    $student = auth()->user();

    if (!$student) {
        abort(401);
    }

    $violations = $student->violations()
        ->orderByDesc('occurred_at')
        ->get();

    // Correct filtering based on your actual status values
    $activeCases = $violations->whereNotIn('status', ['consequence_applied', 'resolved']); // still open / not yet approved
    $pendingCases = $violations->where('status', 'consequence_applied'); // awaiting quiz or under review
    $resolvedCases = $violations->where('status', 'resolved');
    $totalViolations = $violations;

    return view('violations.index', compact(
        'activeCases',
        'resolvedCases',
        'pendingCases',
        'totalViolations'
    ));
}

    public function report()
    {
        $student = auth()->user();

        if (!$student) {
            abort(401);
        }

        $violations = $student->violations()
            ->orderByDesc('occurred_at')
            ->get()
            ->map(function ($violation) {
                $status = strtolower((string) ($violation->status ?? 'pending'));

                $violation->status_label = match ($status) {
                    'resolved' => 'Resolved Case',
                    'pending' => 'Active Case',
                    'consequence_applied' => 'Pending',
                    'dismissed' => 'Dismissed',
                    default => 'Active Case',
                };

                $violation->status_color = match ($status) {
                    'resolved' => 'green',
                    'pending' => 'red',
                    'consequence_applied' => 'yellow',
                    'dismissed' => 'gray',
                    default => 'red',
                };
                return $violation;
            });

        return view('report', compact('violations'));
    }
}