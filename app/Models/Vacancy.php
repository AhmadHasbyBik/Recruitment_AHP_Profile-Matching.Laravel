<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacancy extends Model
{
    protected $fillable = ['position', 'description', 'open_date', 'close_date', 'is_active'];

    protected $casts = [
        'open_date' => 'date',
        'close_date' => 'date',
        'is_active' => 'boolean',
    ];
    
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function idealProfileValues()
    {
        return $this->hasMany(IdealProfileValue::class);
    }
}
