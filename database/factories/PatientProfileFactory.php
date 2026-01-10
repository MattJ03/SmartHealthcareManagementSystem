<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Database\Factories\UserFactory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientProfile>
 */
class PatientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patient = User::factory()->create();
        $doctor = User::factory()->create();

        return [
            'user_id' => $patient->id,
            'emergency_contact' => $this->faker->phoneNumber(),
            'doctor_id' => $doctor->id,
        ];
    }
}
