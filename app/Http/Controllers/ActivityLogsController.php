<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;


class ActivityLogsController extends Controller
{
    public function completeLogList() {

        $user = auth()->user();

        abort_unless($user->hasRole('admin'), 403);

        $logs = ActivityLog::query()
                ->orderBy('created_at', 'desc')
                ->paginate(30);

        if($logs->isEmpty()) {
            return response()->json([
                'message' => 'No logs found'
            ]);
        }
        return response()->json([
            'logs' => $logs,
            'message' => 'Logs retrieved',
        ], 200);
    }
}
