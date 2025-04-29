<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\InterviewResult;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'candidate_id',
        'user_id',
        'schedule_date',
        'notes',
        'status',
        'feedback',
        'feedback_at'
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
        'feedback_at' => 'datetime'
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for status
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function feedbackBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'feedback_by');
    }

    public function scopeOverdue($query)
    {
        return $query->where('schedule_date', '<', now())
            ->where('status', '!=', 'completed');
    }

    public function isOverdue(): bool
    {
        return $this->schedule_date < now() && $this->status !== 'completed';
    }

    public function result()
    {
        return $this->hasOne(InterviewResult::class);
    }


    public function hasResult(): bool
    {
        return $this->result()->exists();
    }
}
