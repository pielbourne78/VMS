<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'violation_id',
        'title',
        'description',
        'passing_score',
    ];

    protected $casts = [
        'passing_score' => 'integer',
    ];

    public function violation(): BelongsTo
    {
        return $this->belongsTo(Violation::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public static function ensureForViolation(Violation $violation): self
    {
        $quiz = $violation->quiz()->firstOrCreate([
            'violation_id' => $violation->id,
            'title' => 'Penalty Quiz',
            'description' => 'This quiz measures understanding of the campus rules related to the reported violation.',
            'passing_score' => 70,
        ]);

        if ($quiz->questions()->count() === 0) {
            $quiz->buildDefaultQuestions();
        }

        return $quiz->fresh(['questions']);
    }

    public function buildDefaultQuestions(): void
    {
        $prompt = $this->buildReasoningPrompt();

        $this->questions()->create([
            'prompt' => $prompt,
            'options' => [],
            'correct_option' => 'reasoning-submission-required',
            'points' => 1,
        ]);
    }

    protected function buildReasoningPrompt(): string
    {
        $type = strtolower((string) ($this->violation?->violation_type ?? ''));

        if (str_contains($type, 'uniform') || str_contains($type, 'dress') || str_contains($type, 'appearance')) {
            return 'Describe the circumstances that led to you not wearing the proper uniform today.';
        }

        if (str_contains($type, 'late') || str_contains($type, 'arrival') || str_contains($type, 'tardiness')) {
            return 'Describe the circumstances that led to your late arrival and explain how you will comply with campus time rules moving forward.';
        }

        if (str_contains($type, 'smok') || str_contains($type, 'vandal') || str_contains($type, 'cheat') || str_contains($type, 'discipline')) {
            return 'Describe the situation surrounding this violation, explain your understanding of the rule, and state how you will prevent this from happening again.';
        }

        if (str_contains($type, 'noise') || str_contains($type, 'behavior') || str_contains($type, 'conduct')) {
            return 'Describe the circumstances behind this conduct violation and explain how you will correct your behavior to follow campus standards.';
        }

        return 'Describe the circumstances surrounding this violation and explain how you understand the rule and will comply going forward.';
    }
}
