<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateCriteria extends Model
{
    protected $table = 'candidate_criterias';
    protected $fillable = ['candidate_id', 'criteria_id', 'value'];
    
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
    
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}