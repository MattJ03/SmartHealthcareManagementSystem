<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;

class DoctorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::role('doctor')->get();

        foreach ($doctors as $doctor) {
            DoctorProfile::create([
                'user_id' => $doctor->id,
                'speciality' => 'General Practice',
                'license_number' => fake()->numberBetween(10000000, 999999999),
                'clinic_hours' => [
                    'monday' => [
                        'start' => '09:00',
                        'end' => '17:00',
                        'interval' => 30,
                    ],
                    'tuesday' => [
                        'start' => '09:00',
                        'end' => '17:00',
                        'interval' => 30,
                    ],
                    'wednesday' => [
                        'start' => '09:00',
                        'end' => '17:00',
                        'interval' => 30,
                    ],
                    'thursday' => [
                        'start' => '09:00',
                        'end' => '17:00',
                        'interval' => 30,
                    ],
                    'friday' => [
                        'start' => '09:00',
                        'end' => '17:00',
                        'interval' => 30,
                    ],
                ],
            ]);
        }
    }
}
