<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Services\AppointmentService;

namespace App\Http\Controllers;
class AppointmentController extends Controller
{
    public function createAppoinment(Request $request, AppointmentService $appointmentService)
    {
        $this->authorize('create appointment');

        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'starts_at' => 'required|',
            'ends_at' => 'required|after:starts_at',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable',
        ]);

        $patientId = auth()->id();
        $appointment = $appointmentService->storeAppointment($patientId, $validatedData);
        return response()->json([
            'appointment' => $appointment,
            'patientId' => $patientId,
        ]);

    }
}
