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

        $activeCases = $violations->where('status', 'pending');
        $resolvedCases = $violations->where('status', 'resolved');
        $pendingCases = $violations->where('status', 'pending');
        $totalViolations = $violations;

        return view('violations.index', compact('activeCases', 'resolvedCases', 'pendingCases', 'totalViolations'));
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
                    'pending' => 'Pending',
                    'consequence_applied' => 'Active Case',
                    'dismissed' => 'Dismissed',
                    default => 'Active Case',
                };

                $violation->status_color = match ($status) {
                    'resolved' => 'green',
                    'pending' => 'yellow',
                    'consequence_applied' => 'red',
                    'dismissed' => 'gray',
                    default => 'red',
                };

                return $violation;
            });

        return view('report', compact('violations'));
    }
}