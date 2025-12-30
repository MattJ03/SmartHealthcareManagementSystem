<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorAvailabilityController extends Controller
{
    public function __invoke(Request $request, $doctor) {
        $doctorId = $doctor;

        $date = Carbon::parse($request->query('date'));
        $dateKey = strToLower($date->format('D'));

        $profile = DoctorProfile::where('user_id', $doctorId)->firstOrFail();

        $hours = $profile->clinic_hours[$dateKey] ?? null;

        if(!$hours) {
            return response()->json([]);
        }
        $start = Carbon::createFromTimeString($hours['start']);
        $end = Carbon::createFromTimeString($hours['end']);
        $interval = $hours['interval'] ?? 30;

        $booked = Appointment::where('doctor_id', $doctorId)
            ->whereDate('starts_at', $date->toDateString())
            ->pluck('starts_at')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $slots = [];
        while ($start < $end) {
            $time = $start->format('H:i');
            if (!in_array($time, $booked)) $slots[] = $time;
            $start->addMinutes($interval);
        }

        return response()->json($slots);
    }

}
