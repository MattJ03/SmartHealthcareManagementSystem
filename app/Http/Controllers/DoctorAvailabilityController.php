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

    public function doctorActivityThisWeek() {
        $user = auth()->user();
        abort_unless($user->hasRole('admin'), 403);

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $doctors = User::role('doctor')
                         ->withCount(['doctorAppointments' => function($query) use ($startOfWeek, $endOfWeek) {
                             $query->whereBetween('starts_at', [$startOfWeek, $endOfWeek]);
                         }])
                          ->orderBy('doctor_appointments_count', 'desc')
                           ->get(['id', 'name']);

        return response()->json([
            'busiest' => $doctors->take(3),
            'least_busy' => $doctors->sortBy('appointments_count')->take(3)->values(),
        ]);
    }


}
