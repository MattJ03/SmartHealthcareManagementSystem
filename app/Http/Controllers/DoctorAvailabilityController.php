<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class DoctorAvailabilityController extends Controller
{
    public function __invoke(Request $request, User $doctor) {
        $profile = $doctor->doctorProfile;
        if(!$profile) {
            return response()-> json([]);
        }
        $doctorId = $doctor->id;

        $date = Carbon::parse($request->query('date'));
        $dateKey = strToLower($date->format('l'));

        $hours = $profile->clinic_hours[$dateKey] ?? null;

        \Log::debug([
            'dateKey' => $dateKey,
            'clinic_hours' => $profile->clinic_hours,
            'hours' => $hours,
        ]);

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
