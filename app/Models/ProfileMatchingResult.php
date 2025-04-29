<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileMatchingResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'final_score',
        'rank',
        'processed_by',
        'notes',
        'processed_at'
    ];

    protected $casts = [
        'close_date' => 'date',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getCriteriaValuesAttribute()
    {
        return $this->candidate->criteriaValues->load('criteria');
    }

    public function vacancy()
    {
        return $this->hasOneThrough(
            Vacancy::class,
            Candidate::class,
            'id', // Foreign key on candidates table
            'id', // Foreign key on vacancies table
            'candidate_id', // Local key on profile_matching_results table
            'vacancy_id' // Local key on candidates table
        );
    }

    public function getIdealValuesAttribute()
    {
        return IdealProfileValue::where('vacancy_id', $this->candidate->vacancy_id)
            ->pluck('value', 'criteria_id')
            ->toArray();
    }
}
