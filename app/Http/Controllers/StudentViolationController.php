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
}