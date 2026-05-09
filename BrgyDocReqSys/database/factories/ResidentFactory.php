<?php

namespace Database\Factories;

use App\Models\Resident;
use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory
{
    protected $model = Resident::class;

    public function definition(): array
    {
        return [
            'household_id' => Household::factory(),
            'full_name' => $this->faker->name(),
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->city(),
            'purok_zone' => 'Purok ' . $this->faker->numberBetween(1, 10),
            'date_of_birth' => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Separated', 'Divorced']),
            'occupation' => $this->faker->randomElement(['Farmer', 'Vendor', 'Teacher', 'Driver', 'Student', 'Office Worker', 'Self-employed']),
            'contact_number' => '09' . $this->faker->numerify('#########'),
        ];
    }
}
