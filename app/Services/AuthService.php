<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\PatientProfile;
class AuthService {

    public function patientRegister(array $data) {
        $user = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
           'contact_number' => $data['contact_number'],
        ]);
        Log::info($user->id . ' logged in');

        $profile = PatientProfile::create([
           'user_id' => $user->id,
           'emergency_contact' => $data['emergency_contact'],
           'doctor_id' => $data['doctor_id'],
        ]);
        Log::info($profile->id . ' logged in');
    }
}
