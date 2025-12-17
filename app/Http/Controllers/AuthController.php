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

    public function doctorRegister(Request $request) {
        $this->authorize('create doctor');

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|confirmed|max:50',
            'contact_number' => 'required|string|max:15|min:8',
            'speciality' => 'nullable|string|max:50',
            'license_number' => 'required|string|min:8|max:15',
            'clinic_hours' => 'required'
        ]);

       $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'contact_number' => $data['contact_number'],
        ]);
        Log::info('User registered successfully');
        $user->assignRole('doctor');

        DoctorProfile::create([
            'user_id' => $user->id,
            'speciality' => $data['speciality'],
            'license_number' => $data['license_number'],
            'clinic_hours' => $data['clinic_hours'],
        ]);

        return response()->json(['message' => 'Doctor Registered Successfully'], 201);
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
        return response()->json(['message' => 'Admin Registered Successfully'], 201);
    }

    public function patientLogin(Request $request, AuthService $authService) {
        $validatedData = $request->validate([
           'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:50',
        ]);
        try {
            $user = $authService->patientLogin($validatedData);
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
               'token' => $token,
               'token_type' => 'Bearer',
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }

    }

    public function doctorLogin(Request $request, AuthService $authService) {
        $validatedData = $request->validate([
           'email' => 'required|email|max:50',
           'password' => 'required|min:8|max:50',
        ]);
        try {
        $user = $authService->doctorLogin($validatedData);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
        } catch(\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode() ?: 400);
        }
    }

    public function adminLogin(Request $request, AuthService $authService) {
        $validatedData = $request->validate([
           'email' => 'required|email|max:50',
           'password' => 'required|min:8|max:50',
        ]);

        try {
            $user = $authService->adminLogin($validatedData);
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}
