<?php

// database/factories/AhpPairwiseComparisonFactory.php

namespace Database\Factories;

use App\Models\AhpPairwiseComparison;
use App\Models\Criteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class AhpPairwiseComparisonFactory extends Factory
{
    protected $model = AhpPairwiseComparison::class;

    public function definition()
    {
        $criteria1 = Criteria::factory()->create();
        $criteria2 = Criteria::factory()->create();
        
        return [
            'criteria1_id' => $criteria1->id,
            'criteria2_id' => $criteria2->id,
            'value' => $this->faker->numberBetween(1, 9),
        ];
    }
}