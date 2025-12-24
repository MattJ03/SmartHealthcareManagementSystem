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
class AppointmentFactory extends Factory
{

    public function definition(): array
    {

        $starts = Carbon::today()->setTime(9, 0);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        return [
            'patient_id' => User::factory(),
            'doctor_id' => $doctor->id,
            'starts_at' => $starts->format("Y-m-d H:i:s"),
            'ends_at' => $starts->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
            'notes' => fake()->paragraph(),
        ];
    }
}
