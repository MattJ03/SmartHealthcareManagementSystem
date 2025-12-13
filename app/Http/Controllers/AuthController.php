<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PatientProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class AuthController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function patientRegister(Request $request) {
        $this->authorize('create patient');

        $data = $request->validate([
           'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|max:50',
            'contact_number' => 'required|string|max:15|min:8',
            'emergency_contact' => 'required|string|max:15|min:8',
            'doctor_id' => 'nullable|exists:users,id',
        ]);
        $user = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
           'contact_number' => $data['contact_number'],
        ]);


        $user->assignRole('patient');


        PatientProfile::create([
           'user_id' => $user->id,
           'emergency_contact' => $data['emergency_contact'],
            'doctor_id' => $data['doctor_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Patient Registered Successfully'
        ], 201);
    }

}
