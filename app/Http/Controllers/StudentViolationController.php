<?php

namespace App\Http\Controllers;

use App\Services\DisciplineDashboardService;
use Illuminate\Http\Request;

class StudentViolationController extends Controller
{
    public function index(Request $request, DisciplineDashboardService $service)
    {
        $student = auth()->user();

        if (!$student) {
            abort(401);
        }

        $data = $service->forStudent($student);

        return view('violations.index', [
            'activeCases' => $data['activeCases'],
            'resolvedCases' => $data['resolvedCases'],
            'pendingCases' => $data['pendingCases'],
            'totalViolations' => $data['totalViolations'],
            'summary' => $data['summary'],
        ]);
    }

    public function dashboard(DisciplineDashboardService $service)
    {
        $student = auth()->user();

        if (!$student) {
            abort(401);
        }

        $data = $service->forStudent($student);

        return view('dashboard', [
            'violations' => $data['violations'],
            'summary' => $data['summary'],
        ]);
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