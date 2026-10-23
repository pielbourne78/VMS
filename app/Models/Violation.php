<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $violation_code
 * @property int $user_id
 * @property int $issued_by
 * @property string $violation_type
 * @property string|null $offense_code
 * @property string|null $offense_category
 * @property int|null $offense_number
 * @property string|null $sanction_code
 * @property string|null $sanction_label
 * @property int $beyond_path
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $occurred_at
 * @property string $status
 * @property string|null $resolution_notes
 * @property int|null $resolved_by
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $case_owner_id
 * @property int|null $recorded_by
 * @property int|null $last_updated_by
 * @property string|null $escalated_at
 * @property string|null $escalation_reason
 * @property string $review_level
 * @property-read \App\Models\User|null $caseOwner
 * @property-read string $display_status
 * @property-read string $display_status_class
 * @property-read string|null $next_action_route
 * @property-read string|null $offense_ordinal
 * @property-read \App\Models\User $issuedBy
 * @property-read \App\Models\User|null $lastUpdatedBy
 * @property-read \App\Models\Quiz|null $quiz
 * @property-read \App\Models\User|null $recordedBy
 * @property-read \App\Models\User|null $resolvedBy
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereBeyondPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereCaseOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereEscalatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereEscalationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereOccurredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereOffenseCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereOffenseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereOffenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereRecordedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereResolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereReviewLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereSanctionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereSanctionLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereViolationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation whereViolationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Violation withoutTrashed()
 * @mixin \Eloquent
 */
class Violation extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONSEQUENCE_APPLIED = 'consequence_applied';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_DISMISSED = 'dismissed';

    public function isOpen(): bool
    {
        return !in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_DISMISSED], true);
    }

    public function isEscalated(): bool
    {
        return $this->review_level === 'escalated' || !is_null($this->escalated_at);
    }

    public function requiresAction(): bool
    {
        return $this->status === self::STATUS_PENDING || $this->status === self::STATUS_CONSEQUENCE_APPLIED;
    }

    public function getDisplayStatusAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Active Case',
            self::STATUS_CONSEQUENCE_APPLIED => 'Pending',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_DISMISSED => 'Dismissed',
            default => ucfirst((string) $this->status),
        };
    }

    public function getDisplayStatusClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-orange-100 text-orange-700',
            self::STATUS_CONSEQUENCE_APPLIED => 'bg-blue-100 text-blue-700',
            self::STATUS_RESOLVED => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getOffenseOrdinalAttribute(): ?string
    {
        $n = (int) ($this->offense_number ?? 0);

        if ($n <= 0) {
            return null;
        }

        if (in_array($n % 100, [11, 12, 13], true)) {
            return $n . 'th';
        }

        return $n . (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$n % 10]);
    }

    public function getNextActionRouteAttribute(): ?string
    {
        if ($this->status === self::STATUS_CONSEQUENCE_APPLIED && $this->student_id === auth()->id()) {
            return route('student.penalty.quiz', $this);
        }

        return null;
    }

    public static function caseIdFor(string $offenseCode, int $offenseNumber, ?int $userId = null): string
    {
        $code = strtoupper(trim((string) $offenseCode));

        if ($userId === null) {
            return sprintf('%s-%02d', $code, $offenseNumber);
        }

        $suffix = substr((string) $userId, -3);

        return sprintf('%s-%s-%02d', $code, $suffix, $offenseNumber);
    }

    protected $fillable = [
        'violation_code',
        'user_id',
        'issued_by',
        'case_owner_id',
        'recorded_by',
        'last_updated_by',
        'violation_type',
        'description',
        'occurred_at',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
        'deleted_at',
        'offense_code', 
        'offense_category', 
        'offense_number', 
        'sanction_code', 
        'sanction_label', 
        'beyond_path',
        'escalated_at',
        'escalation_reason',
        'review_level',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function caseOwner()
    {
        return $this->belongsTo(User::class, 'case_owner_id');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function hasPendingQuiz(): bool
    {
        return $this->status === 'consequence_applied' && $this->quiz()->exists();
    }
    public static function sanctionFor(string $code, int $number): array
{
    $offense = \App\Models\Offense::where('code', $code)->firstOrFail();

    if ($offense->category === 'minor') {
        $steps = config('offenses.minor_path');
        return [
            'letter' => null,
            'label' => $steps[min($number, count($steps)) - 1],
            'beyond' => $number > count($steps),
        ];
    }

    $path = $offense->sanction_path;
    $letter = $path[min($number, count($path)) - 1];

    return [
        'letter' => $letter,
        'label' => $letter . ' - ' . config("offenses.sanction_types.$letter"),
        'beyond' => $number > count($path),
    ];
}
}