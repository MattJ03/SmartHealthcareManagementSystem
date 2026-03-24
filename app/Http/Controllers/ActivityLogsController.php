<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;


class ActivityLogsController extends Controller
{
    public function completeLogList()
    {

        $user = auth()->user();

        abort_unless($user->hasRole('admin'), 403);

        $logs = ActivityLog::query()
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        if ($logs->total() === 0) {
            return response()->json([
                'message' => 'No logs found'
            ]);
        }
        return response()->json([
            'logs' => $logs,
            'message' => 'Logs retrieved',
        ], 200);
    }

    public function patientLogList() {
        $user = auth()->user();
        abort_unless($user->hasRole('patient'), 403);

        $logs = ActivityLog::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                       ->orWhere('patient_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        if($logs->total() === 0) {
            return response()->json([
                'message' => 'No logs found',
            ]);
        }

        return response()->json([
            'logs' => $logs,
            'message' => 'Logs retrieved',
        ], 200);
    }

    public function doctorLogList() {
        $user = auth()->user();
        abort_unless($user->hasRole('doctor'), 403);

        $logs = ActivityLog::query()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                           ->orWhere('doctor_id', $user->id);
                })
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        if($logs->total() === 0) {
            return response()->json([
                'message' => 'No logs found',
            ]);
        }

        return response()->json([
            'logs' => $logs,
            'message' => 'Logs retrieved',
        ]);
    }
}




