<?php

namespace Database\Factories;

use App\Models\DocumentRequest;
use App\Models\Resident;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentRequestFactory extends Factory
{
    protected $model = DocumentRequest::class;

    public function definition(): array
    {
        $statuses = array_keys(DocumentRequest::statuses());
        $status = $this->faker->randomElement($statuses);
        $isPaid = $status !== DocumentRequest::STATUS_PENDING ? $this->faker->boolean(60) : $this->faker->boolean(20);

        return [
            'resident_id' => Resident::inRandomOrder()->first()?->id ?? Resident::factory(),
            'document_type_id' => DocumentType::inRandomOrder()->first()?->id ?? DocumentType::factory(),
            'purpose' => $this->faker->sentence(8),
            'status' => $status,
            'payment_amount' => $isPaid ? $this->faker->randomFloat(2, 30, 500) : null,
            'payment_date' => $isPaid ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'receipt_number' => $isPaid ? strtoupper('RCPT-' . $this->faker->unique()->numerify('####')) : null,
            'is_paid' => $isPaid,
        ];
    }
}
