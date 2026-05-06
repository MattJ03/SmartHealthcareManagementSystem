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

    public function getPatientIndex() {
        $user = auth()->user();
        abort_unless($user->hasRole('admin'), 403);

        $patients = User::role('patient')->select('id', 'name')->get();
        $numberPatients = $patients->count();

        if($patients->isEmpty()) {
            return response()->json([
                'patients' => [],
                'message' => 'No patients found',
            ]);
        }

        return response()->json([
            'patients' => $patients,
            'message' => 'Patients retrieved successfully',
            'numberPatients' => $numberPatients,
        ]);
    }

    public function getDoctorsPatients(Request $request) {
        $user = auth()->user();

        abort_unless($user->hasRole('doctor'), 403);

        $query = PatientProfile::where('doctor_id', $user->id)
                                    ->with('user:id,name,email,contact_number');
        if($request->filled('search')) {
            $search = $request->query('search');

            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        $patients = $query->get();

        return response()->json([
            'patients' => $patients,
            'message' => 'Patients retrieved successfully',
        ], 200);
    }


}
