<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentService {

    public function storeAppointment(int $patientId, array $data): Appointment {
        return DB::transaction(function () use ($patientId, $data) {
            $conflicts = Appointment::where('doctor_id', $data['doctor_id'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($data) {
                    $query->where('starts_at', '<=', $data['ends_at'])
                    ->where('ends_at', '>=', $data['starts_at']);
                })
                ->lockForUpdate()
                ->exists();
            if($conflicts) {
                throw ValidationException::withMessages([
                   'starts_at' => 'The start date and end date has already been taken.',
                ]);
            }

            $doctorId = User::findOrFail($data['doctor_id']);
            abort_unless($doctorId->hasRole('doctor'), 403);


            return Appointment::create([
                'patient_id' => $patientId,
                'doctor_id' => $data['doctor_id'],
                'starts_at' => Carbon::parse($data['starts_at']),
                'ends_at' => Carbon::parse($data['ends_at']),
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);
        });

    }

}
