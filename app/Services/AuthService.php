<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\PatientProfile;
class AuthService {

    public function patientLogin(array $data): User {
        $patient = User::where('email', $data['email'])->first();
        if(!$patient) {
            throw new \Exception('User not found', 404);
        }
        if(!Hash::check($data['password'], $patient->password)) {
            throw new \Exception('wrong password', 403);
        }
        return $patient;
    }

    }

