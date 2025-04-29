<?php

// database/factories/WeightFactory.php

namespace Database\Factories;

use App\Models\Criteria;
use App\Models\Weight;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightFactory extends Factory
{
    protected $model = Weight::class;

    public function definition()
    {
        return [
            'criteria_id' => Criteria::factory(),
            'weight' => $this->faker->randomFloat(2, 0.1, 1),
        ];
    }
}