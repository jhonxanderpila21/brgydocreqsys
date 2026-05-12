<?php

namespace Database\Seeders;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Barangay Admin',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'role' => User::ROLE_STAFF,
        ]);

        User::factory()->create([
            'name' => 'Resident User',
            'email' => 'resident@example.com',
            'role' => User::ROLE_RESIDENT,
        ]);

        DocumentType::factory()
            ->count(10)
            ->state(new Sequence(
                ['name' => 'Barangay Clearance', 'processing_fee' => 50.00, 'required_information' => 'Valid ID, purpose, proof of residency'],
                ['name' => 'Indigency Certificate', 'processing_fee' => 30.00, 'required_information' => 'Valid ID, reason for request, household details'],
                ['name' => 'Residency Certificate', 'processing_fee' => 40.00, 'required_information' => 'Valid ID, address, length of stay'],
                ['name' => 'Cedula / Community Tax Certificate', 'processing_fee' => 20.00, 'required_information' => 'Valid ID, income details'],
                ['name' => 'Business Permit Support Letter', 'processing_fee' => 100.00, 'required_information' => 'Business name, owner details, permit requirements'],
                ['name' => 'Scholarship Clearance', 'processing_fee' => 35.00, 'required_information' => 'Student ID, proof of enrollment, reason for request'],
                ['name' => 'NBI Clearance Support Letter', 'processing_fee' => 70.00, 'required_information' => 'Valid ID, purpose, photograph specifications'],
                ['name' => 'Barangay ID Application', 'processing_fee' => 60.00, 'required_information' => 'Birth certificate, valid ID, proof of residency'],
                ['name' => 'Solo Parent Certification', 'processing_fee' => 45.00, 'required_information' => 'Birth certificate, marriage certificate, affidavit of support'],
                ['name' => 'Calamity Assistance Certificate', 'processing_fee' => 25.00, 'required_information' => 'Affected area proof, valid ID, description of damages'],
            ))
            ->create();

        Household::factory()
            ->count(10)
            ->create()
            ->each(fn (Household $household) => Resident::factory()
                ->count(3)
                ->create(['household_id' => $household->id])
            );

        DocumentRequest::factory()
            ->count(30)
            ->create();
    }
}
