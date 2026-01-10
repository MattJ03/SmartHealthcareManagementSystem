<?php

namespace Database\Factories;
use App\Models\User;
use Database\Factories\UserFactory;
use App\Models\MedicalRecord;
use Illuminate\Support\Str;
use App\Models\PatientProfile;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $doctor->id,
        ]);

        $extension = fake()->randomElement(['pdf, docx, doc, ppt, pptx, odt, png, jpg, jpeg']);
        $fileName = Str::uuid() . '.' . $extension;



        return [
            'patient_id' => $patientProfile->id,
            'file_path' => "medical-records/{$fileName}",
            'file_type' => $extension,
            'file_size' => fake()->numberBetween(50_000, 2_000_000),
            'title' => fake()->title(),
            'notes' => fake()->text(),

        ];
    }
}
