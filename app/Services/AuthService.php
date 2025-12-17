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

    public function doctorLogin(array $data): User {
        $doctor = User::where('email', $data['email'])->first();

        if(!$doctor) {
            throw new \Exception('User not found', 404);
        }
        if(!Hash::check($doctor->password, $data['password'])) {
            throw new \Exception('wrong password',  403);
        }
        return $doctor;
    }

    public function adminLogin(array $data): User {
        $admin = User::where('email', $data['email'])->first();
        if(!$admin) {
            throw new \Exception('User not found', 404);
        }
        if(!Hash::check($data['password'], $admin->password)) {
            throw new \Exception('wrong password', 403);
        }
        return $admin;
    }

    }

