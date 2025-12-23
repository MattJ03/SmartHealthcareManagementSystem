<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Appointment;
use Database\Factories\UserFactory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppointmentFactorFactory extends Factory
{

    public function definition(): array
    {

        $starts = Carbon::today()->setTime(9, 0);
        return [
            'patient_id' => User::factory(),
            'doctor_id' => User::factory(),
            'starts_at' => fake()->time(),
            'ends_at' => (clone $starts)->addMinutes(30),
            'status' => 'confirmed',
            'notes' => fake()->paragraph(),
        ];
    }
}
