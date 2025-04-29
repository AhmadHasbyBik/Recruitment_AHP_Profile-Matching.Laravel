<?php

// database/factories/ProfileMatchingResultFactory.php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\ProfileMatchingResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileMatchingResultFactory extends Factory
{
    protected $model = ProfileMatchingResult::class;

    public function definition()
    {
        return [
            'candidate_id' => Candidate::factory(),
            'final_score' => $this->faker->randomFloat(2, 60, 100),
            'rank' => $this->faker->numberBetween(1, 10),
        ];
    }
}