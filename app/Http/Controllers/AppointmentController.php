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



        $patientId = auth()->id();
        $appointment = $appointmentService->storeAppointment($patientId, $validatedData);
        return response()->json([
            'appointment' => $appointment,
            'patient_id' => $patientId,
        ], 201);

    }
}
