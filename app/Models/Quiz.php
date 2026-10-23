<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $violation_id
 * @property string $title
 * @property string|null $description
 * @property int $passing_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizAttempt> $attempts
 * @property-read int|null $attempts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \App\Models\Violation|null $violation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz wherePassingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereViolationId($value)
 * @mixin \Eloquent
 */
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

        $quiz->normalizeLegacyPrompts();

        return $quiz->fresh(['questions']);
    }

    public function normalizeLegacyPrompts(): void
    {
        $replacementPrompt = $this->buildReasoningPrompt();

        $this->questions()->get()->each(function ($question) use ($replacementPrompt) {
            if ($this->shouldReplacePrompt((string) $question->prompt)) {
                $question->update(['prompt' => $replacementPrompt]);
            }
        });
    }

    protected function shouldReplacePrompt(string $prompt): bool
    {
        return self::hasLegacyPromptPhrase($prompt);
    }

    protected static function hasLegacyPromptPhrase(string $prompt): bool
    {
        $normalizedPrompt = strtolower($prompt);

        return str_contains($normalizedPrompt, 'moving forward')
            || str_contains($normalizedPrompt, 'going forward');
    }

    public function buildDefaultQuestions(): void
    {
        $this->questions()->create([
            'prompt' => $this->buildReasoningPrompt(),
            'options' => [],
            'correct_option' => 'reasoning-submission-required',
            'points' => 1,
        ]);
    }

    protected function buildReasoningPrompt(): string
    {
        $type = strtolower((string) ($this->violation?->violation_type ?? ''));

        $templates = [
            ['matches' => ['uniform', 'dress', 'appearance'], 'prompt' => 'Describe the circumstances that led to you not wearing the proper uniform today.'],
            ['matches' => ['late', 'arrival', 'tardiness'], 'prompt' => 'Describe the circumstances that led to your late arrival and explain how you will follow campus attendance rules and avoid future violations.'],
            ['matches' => ['smok', 'vandal', 'cheat', 'discipline'], 'prompt' => 'Describe the situation surrounding this violation, explain your understanding of the rule, and state how you will prevent this from happening again.'],
            ['matches' => ['noise', 'behavior', 'conduct'], 'prompt' => 'Describe the circumstances behind this conduct violation and explain how you will correct your behavior to follow campus standards.'],
        ];

        foreach ($templates as $template) {
            foreach ($template['matches'] as $keyword) {
                if (str_contains($type, $keyword)) {
                    return $template['prompt'];
                }
            }
        }

        return 'Describe the circumstances surrounding this violation and explain how you understand the rule and how you will comply with campus policies to avoid future violations.';
    }
}
