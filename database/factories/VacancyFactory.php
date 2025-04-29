<?php

// database/factories/VacancyFactory.php

namespace Database\Factories;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');
        
        return [
            'position' => $this->faker->jobTitle,
            'description' => $this->faker->paragraphs(3, true),
            'open_date' => $startDate,
            'close_date' => $endDate,
            'is_active' => $this->faker->boolean(80),
        ];
    }
}