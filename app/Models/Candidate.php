<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'address', 'vacancy_id', 'resume'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function latestInterview()
    {
        return $this->hasOne(InterviewSchedule::class)->latestOfMany();
    }

    public function criteriaValues()
    {
        return $this->hasMany(CandidateCriteria::class);
    }

    public function results()
    {
        return $this->hasMany(ProfileMatchingResult::class);
    }

    public function latestInterviewSchedule()
    {
        return $this->hasOne(InterviewSchedule::class)->latestOfMany();
    }

    public function interviewResults()
    {
        return $this->hasManyThrough(
            InterviewResult::class,
            InterviewSchedule::class,
            'candidate_id',
            'interview_schedule_id',
            'id',
            'id'
        );
    }

    public function latestInterviewResult()
    {
        return $this->hasOne(InterviewResult::class)
            ->through('interviewSchedules')
            ->latestOfMany();
    }
}
