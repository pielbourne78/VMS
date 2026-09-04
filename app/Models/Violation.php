<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    protected $fillable = [
        'violator_name',
        'student_id',
        'violation_type',
        'violation_datetime',
        'description',
        'location',
        'notification_alert',
        'student_notification',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'violation_datetime' => 'datetime',
            'notification_alert' => 'boolean',
            'student_notification' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id', 'student_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}