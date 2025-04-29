<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IdealProfileValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_id',
        'criteria_id',
        'value'
    ];

    protected $casts = [
        'value' => 'integer'
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}