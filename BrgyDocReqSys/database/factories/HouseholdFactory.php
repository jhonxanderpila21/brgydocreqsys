<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseholdFactory extends Factory
{
    protected $model = Household::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->lastName() . ' Family',
            'purok_zone' => 'Purok ' . $this->faker->numberBetween(1, 10),
        ];
    }
}
