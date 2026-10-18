<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Violation extends Model
{
    public function getDisplayStatusAttribute(): string
{
    return match ($this->status) {
        'pending' => 'Active Case',
        'consequence_applied' => 'Pending',
        'resolved' => 'Resolved',
        'dismissed' => 'Dismissed',
        default => ucfirst((string) $this->status),
    };
}

public function getDisplayStatusClassAttribute(): string
{
    return match ($this->status) {
        'pending' => 'bg-orange-100 text-orange-700',
        'consequence_applied' => 'bg-blue-100 text-blue-700',
        'resolved' => 'bg-green-100 text-green-700',
        default => 'bg-gray-100 text-gray-600',
    };
}
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'violation_code',
        'user_id',
        'issued_by',
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
    [$category, , $path] = array_pad(config("offenses.offenses.$code"), 3, null);

    if ($category === 'minor') {
        $steps = config('offenses.minor_path');
        $beyond = $number > count($steps);
        return ['letter' => null, 'label' => $steps[min($number, count($steps)) - 1], 'beyond' => $beyond];
    }

    $beyond = $number > count($path);
    $letter = $path[min($number, count($path)) - 1];
    return ['letter' => $letter, 'label' => $letter . ' - ' . config("offenses.sanction_types.$letter"), 'beyond' => $beyond];
}
}