<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function availability(User $doctor, Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['message' => 'Date required'], 400);
        }

        $doctorProfile = $doctor->profile;
        if (!$doctorProfile) {
            return response()->json(['slots' => []]);
        }

        $clinicHours = explode('-', $doctorProfile->clinic_hours);
        $startTime = $clinicHours[0];
        $endTime = $clinicHours[1];

        $slots = [];
        $current = strtotime("$date $startTime");
        $end = strtotime("$date $endTime");

        while ($current + 30 * 60 <= $end) {
            $slot = date('H:i', $current);
            $slots[] = $slot;
            $current += 30 * 60;
        }

        $booked = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->whereDate('starts_at', $date)
            ->pluck('starts_at')
            ->map(fn($d) => date('H:i', strtotime($d)))
            ->toArray();

        $available = array_diff($slots, $booked);

        return response()->json(array_values($available));
    }
}
