<?php

// database/factories/CandidateCriteriaFactory.php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\CandidateCriteria;
use App\Models\Criteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateCriteriaFactory extends Factory
{
    protected $model = CandidateCriteria::class;

    public function definition()
    {
        return [
            'candidate_id' => Candidate::factory(),
            'criteria_id' => Criteria::factory(),
            'value' => $this->faker->randomFloat(2, 1, 5),
        ];
    }
}
