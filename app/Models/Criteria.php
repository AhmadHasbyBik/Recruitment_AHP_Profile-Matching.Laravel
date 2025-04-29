<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Criteria extends Model
{
    use HasFactory;
    
    protected $fillable = ['code', 'name', 'criteria_status_id', 'type', 'description'];
    
    public function candidateValues(): HasMany
    {
        return $this->hasMany(CandidateCriteria::class);
    }
    
    public function status(): BelongsTo
    {
        return $this->belongsTo(CriteriaStatus::class, 'criteria_status_id');
    }
    
    // Add this new relationship for AHP weights
    public function ahpComparison(): HasOne
    {
        return $this->hasOne(AhpPairwiseComparison::class, 'criteria1_id');
    }
    
    // Add accessor for weight
    public function ahpWeights()
    {
        return $this->hasMany(AhpPairwiseComparison::class, 'criteria1_id');
    }
    
    // Ganti accessor untuk mendapatkan bobot AHP
    public function getAhpWeightAttribute()
    {
        $weights = AhpPairwiseComparison::calculateWeights();
        return $weights['weights'][$this->id] ?? 1; // Default weight is 1 if not set
    }
    
    
    public static function getTypeOptions(): array
    {
        return [
            'core' => 'Core',
            'secondary' => 'Secondary',
        ];
    }
}