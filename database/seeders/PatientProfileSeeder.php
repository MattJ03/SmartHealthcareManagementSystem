<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PatientProfile;


class PatientProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::role('patient')->get();
        $doctors = User::role('doctor')->get();

        foreach ($patients as $patient) {
            PatientProfile::create([
                'user_id' => $patient->id,
                'doctor_id' => $doctors->random()->id,
                'emergency_contact' => fake()->phoneNumber(),
            ]);
        }
    }
}
