<?php

namespace Database\Seeders;

use App\Models\PatientProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\AppointmentFactory;
use App\Models\User;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{


    public function run(): void
    {
        $profiles = PatientProfile::all();

        foreach($profiles as $profile) {
            Appointment::factory()->count(rand(2, 5))->create([
                'patient_id' => $profile->id,
                'doctor_id' => $profile->doctor->id,
            ]);
        }


    }
}
