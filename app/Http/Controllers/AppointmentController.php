<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Database\Seeders\RolePermissionSeeder;
use App\Policies\AppointmentPolicy;
class AppointmentController extends Controller
{
    public function storeAppointment(Request $request, AppointmentService $appointmentService)
    {
        $this->authorize('create', Appointment::class);

        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'starts_at' => 'required',
            'ends_at' => 'required|after:starts_at',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable',
        ]);

        $user = auth()->user();

        if($user->hasRole('patient')) {
            $patientId = $user->id;
        }

        if($user->hasRole('admin')) {
            $request->validate([
                'patient_id' => 'required|exists:users,id',
                ]);

            $patient = User::findOrFail($request->patient_id);
            abort_unless($patient->hasRole('patient'), 403);
            $patientId = $patient->id;

        }


        $appointment = $appointmentService->storeAppointment($patientId, $validatedData);
        return response()->json([
            'appointment' => $appointment,
            'patient_id' => $patientId,
        ], 201);

    }

    public function updateAppointment(Request $request, AppointmentService $appointmentService) {
        $appointment = Appointment::findOrFail($request->id);
        $this->authorize('update', $appointment);

        $validatedData = $request->validate([
           'doctor_id' => 'required|exists:users,id',
           'starts_at' => 'required',
            'ends_at' => 'required|after:starts_at',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable',
        ]);


        $user = auth()->user();
        if($user->hasRole('patient')) {
            abort_unless($user->id === $appointment->patient_id, 403);
        }
        $appointment = $appointmentService->updateAppointment($appointment->id, $validatedData);

        return response()->json([
           'appointment' => $appointment,
           'message' => 'Appointment updated successfully',
        ]);
    }

    public function getAllMyAppointments() {
        $user = auth()->user();

        abort_unless($user->hasRole(['patient']), 403);

        $appointments = Appointment::with('doctor')
                                        ->where('patient_id', $user->id)
                                     ->where('status', 'confirmed')
                                     ->where('starts_at', '>=', now())
                                      ->orderBy('starts_at')
                                     ->get();

        if($appointments->isEmpty()) {
            return response()->json([
                'message' => 'there are no appointments',
                'appointments' => [],
            ], 200);

        }
        return response()->json([
            'appointments' => $appointments,
            'message' => 'All appointments',
        ], 200);
    }

    public function deleteAppointment(Request $request, $id) {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);
        $this->authorize('delete', $appointment);

        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted'], 200);
    }

    public function getAppointment($id) {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);
        $this->authorize('view', $appointment);

        return response()->json([
            'appointment' => $appointment,
            'doctor_name' => $appointment->doctor->name,
            'message' => 'appointment details',
        ], 200);
    }

    public function getAllDoctorAppointments() {
        $user = auth()->user();
        abort_unless($user->hasRole('doctor'), 403);
        $appointments = Appointment::with('patient')
                                         ->where('doctor_id', auth()->id())
                                         ->where('status', 'confirmed')
                                         ->where('starts_at', '>=', now())
                                          ->orderBy('starts_at')
                                           ->get();
        if($appointments->isEmpty()) {
            return response()->json([
                'appointments' => [],
                'message' => 'there are no upcoming appointments',
            ]);
        }
        return response()->json([
            'appointments' => $appointments,
            'message' => 'All appointments',
        ]);
    }

    public function getSoonestAppointment() {
        $user = auth()->user();
        return $user;
    }

}
