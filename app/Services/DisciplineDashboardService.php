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
            $summary['total']++;

            $status = $this->normalizeValue($violation->status ?? 'pending');
            $category = $this->normalizeValue($violation->offense_category ?? '');

            if ($this->isStatus($status, 'pending')) {
                $summary['active']++;
            }

            if ($this->isStatus($status, 'consequence_applied')) {
                $summary['pending']++;
            }

            if ($this->isStatus($status, 'resolved')) {
                $summary['resolved']++;
            }

            if ($this->isStatus($status, 'dismissed')) {
                $summary['dismissed']++;
            }

            if ($this->isCategory($category, 'major')) {
                $summary['major']++;
            }

            if ($this->isCategory($category, 'minor')) {
                $summary['minor']++;
            }

            if ($this->isEscalated($violation)) {
                $summary['escalated']++;
            }
        }

        return $summary;
    }

    protected function normalizeValue(?string $value): string
    {
        return strtolower((string) $value);
    }

    protected function isStatus(string $status, string $expected): bool
    {
        return $status === $expected;
    }

    protected function isCategory(string $category, string $expected): bool
    {
        return $category === $expected;
    }

    protected function isEscalated($violation): bool
    {
        return $this->normalizeValue($violation->review_level ?? '') === 'escalated'
            || !is_null($violation->escalated_at);
    }
}
