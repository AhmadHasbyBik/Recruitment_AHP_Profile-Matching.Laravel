<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_schedule_id',
        'user_id',
        'score',
        'strengths',
        'weaknesses',
        'recommendation',
        'notes',
        'decision'
    ];

    protected $casts = [
        'score' => 'decimal:2'
    ];

    public function interviewSchedule()
    {
        return $this->belongsTo(InterviewSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for decision
    public function isAccepted(): bool
    {
        return $this->decision === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->decision === 'rejected';
    }

    public function isOnHold(): bool
    {
        return $this->decision === 'hold';
    }

    public function getDecisionBadgeAttribute(): string
    {
        return match($this->decision) {
            'accepted' => '<span class="badge bg-success">Accepted</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-warning">On Hold</span>'
        };
    }
}