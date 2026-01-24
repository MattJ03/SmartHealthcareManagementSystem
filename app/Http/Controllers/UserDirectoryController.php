<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use Illuminate\Http\Request;
use App\Models\User;

class UserDirectoryController extends Controller
{

    public function getDoctors() {
        $doctors = User::role('doctor')->select('id', 'name')->get();
        if($doctors->isEmpty()) {
            return response()->json([
                'doctors' => [],
                'message' => 'No doctors found',
            ]);
        }

        return response()->json([
            'doctors' => $doctors,
            'message' => 'Doctors retrieved successfully',
        ]);
    }

    public function getDoctorsPatients(Request $request) {
        $user = auth()->user();

        abort_unless($user->hasRole('doctor'), 403);

        $patients = PatientProfile::where('doctor_id', $user->id)
                                    ->with('user:id,name')
                                    ->get();
        if($patients->isEmpty()) {
            return response()->json([
                'patients' => [],
                'message' => 'No patients found',
            ]);
        }

        return response()->json([
            'patients' => $patients,
            'message' => 'Patients retrieved successfully',
        ], 200);
    }
}
