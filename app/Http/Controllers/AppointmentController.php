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
        $this->authorize('update', Appointment::class);

        $validatedData = $request->validate([
           'doctor_id' => 'required|exists:users,id',
           'starts_at' => 'required',
            'ends_at' => 'required|after:starts_at',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable',
        ]);
        $appointment = Appointment::findOrFail($request->id);

        $user = auth()->user();
        if($user->hasRole('patient')) {
            abort_unless($user->id === $appointment->patient_id, 403);
        }
        $appointment = $appointmentService->updateAppointment($appointment->id, $validatedData);



    }
}
