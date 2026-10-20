<?php

namespace App\Services;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Support\Collection;

class DisciplineDashboardService
{
    public function forStudent(User $user): array
    {
        $violations = $user->violations()->orderByDesc('occurred_at')->get();

        return [
            'violations' => $violations,
            'summary' => $this->summarize($violations),
            'activeCases' => $violations->where('status', 'pending'),
            'pendingCases' => $violations->where('status', 'consequence_applied'),
            'resolvedCases' => $violations->where('status', 'resolved'),
            'totalViolations' => $violations,
        ];
    }

    public function forAdmin(): array
    {
        $violations = Violation::query()
            ->with('student')
            ->orderByDesc('occurred_at')
            ->get();

        return [
            'violations' => $violations,
            'stats' => $this->summarize($violations),
            'recent' => $violations->take(8),
        ];
    }

    protected function summarize(Collection $violations): array
    {
        $summary = [
            'total' => 0,
            'active' => 0,
            'pending' => 0,
            'resolved' => 0,
            'dismissed' => 0,
            'major' => 0,
            'minor' => 0,
            'escalated' => 0,
        ];

        foreach ($violations as $violation) {
            $status = strtolower((string) ($violation->status ?? 'pending'));
            $category = strtolower((string) ($violation->offense_category ?? ''));

            $summary['total']++;

            if ($status === 'pending') {
                $summary['active']++;
            }

            if ($status === 'consequence_applied') {
                $summary['pending']++;
            }

            if ($status === 'resolved') {
                $summary['resolved']++;
            }

            if ($status === 'dismissed') {
                $summary['dismissed']++;
            }

            if ($category === 'major') {
                $summary['major']++;
            }

            if ($category === 'minor') {
                $summary['minor']++;
            }

            if ($violation->review_level === 'escalated' || !is_null($violation->escalated_at)) {
                $summary['escalated']++;
            }
        }

        return $summary;
    }
}
