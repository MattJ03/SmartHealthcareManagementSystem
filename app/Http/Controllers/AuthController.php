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
use App\Models\DoctorProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function patientRegister(Request $request) {
        $this->authorize('create patient');

        $activeUser = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|max:50',
            'contact_number' => 'required|string|max:15|min:8',
            'emergency_contact' => 'required|string|max:15|min:8',
            'doctor_id' => 'required|exists:users,id',  // Make it required!
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
            'doctor_id' => $data['doctor_id'],
        ]);

        ActivityLog::create([
            'user_id' => $activeUser->id,
            'patient_id' => $user->id,
            'doctor_id' => $data['doctor_id'],
            'action' => 'patient_registered',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => $user->name  . ' was registered and assigned to Dr. ' . $user->profile->doctor->name,
        ]);

        return response()->json(['message' => 'Patient registered successfully'], 201);
    }


    public function doctorRegister(Request $request) {
        $this->authorize('create doctor');

        $activeUser = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|confirmed|max:50',
            'contact_number' => 'required|string|max:15|min:8',
            'speciality' => 'string|max:50',
            'license_number' => 'required|string|min:8|max:15',
            'clinic_hours' => 'required|array'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'contact_number' => $data['contact_number'],
        ]);

        $user->assignRole('doctor');

        DoctorProfile::create([
            'user_id' => $user->id,
            'speciality' => $data['speciality'],
            'license_number' => $data['license_number'],
            'clinic_hours' => $data['clinic_hours'],
        ]);

        ActivityLog::create([
            'user_id' => $activeUser->id,
            'doctor_id' => $user->id,
            'action' => 'doctor_registered',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => 'Dr. ' . $user->name . ' was registered as a doctor',
        ]);

        return response()->json(['message' => 'Doctor registered successfully', 'doctor_id' => $user->id], 201);
    }


    public function adminRegister(Request $request) {
        $this->authorize('create admin');

        $data = $request->validate([
           'name' => 'required|string|max:50',
           'email' => 'required|email|max:50',
           'password' => 'required|min:8|confirmed|max:50',
           'contact_number' => 'required|string|max:15|min:8',
        ]);

        $createdUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'contact_number' => $data['contact_number'],
            ]);
        Log::info($createdUser->id . ' account created, next will be assigned to admin role');
        $createdUser->assignRole('admin');
        Log::info($createdUser->id . ' assigned to admin role');
        return response()->json(['message' => 'Admin Registered Successfully',
                                  'user' => $createdUser], 201);
    }

    public function login(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:50',
        ]);
        $user = User::where('email', $validatedData['email'])->first();
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if(!Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect password'], 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
           'token' => $token,
           'token_type' => 'Bearer',
           'role' => $user->getRoleNames()->first(),
            'name' => $user->name,
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
