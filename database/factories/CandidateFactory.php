<?php

// database/factories/CandidateFactory.php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'vacancy_id' => Vacancy::factory(),
            'resume' => $this->faker->optional()->url,
        ];
    }
}
