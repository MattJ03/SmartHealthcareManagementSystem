<?php

namespace App\Http\Controllers;

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
}
