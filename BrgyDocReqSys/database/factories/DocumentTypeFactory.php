<?php

namespace Database\Factories;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    protected $model = DocumentType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'processing_fee' => $this->faker->randomFloat(2, 50, 300),
            'required_information' => $this->faker->sentence(8),
        ];
    }
}
