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

        return [
            'user_id' => User::factory(),
            'emergency_contact' => fake()->phoneNumber(),
            'doctor_id' => User::factory(),
        ];
    }
}
