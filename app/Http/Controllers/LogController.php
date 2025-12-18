<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Get activity logs for real-time display
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request)
    {
        try {
            // Get limit from query parameter (default 100)
            $limit = $request->query('limit', 100);
            
            // Get last timestamp for real-time updates if provided
            $since = $request->query('since', null);

            $query = DB::table('audit_logs')
                ->leftJoin('admins', 'audit_logs.user_id', '=', 'admins.id')
                ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
                ->select(
                    'audit_logs.id',
                    DB::raw("COALESCE(CONCAT(admins.first_name, ' ', COALESCE(admins.middle_initial, ''), ' ', admins.last_name), users.username, 'Unknown') as name"),
                    DB::raw("COALESCE(admins.role, users.role, 'Staff') as role"),
                    'audit_logs.action',
                    'audit_logs.details',
                    'audit_logs.created_at'
                )
                ->orderBy('audit_logs.created_at', 'desc');

            // If 'since' is provided, only get logs after that timestamp (for real-time updates)
            if ($since) {
                $query->where('audit_logs.created_at', '>', $since);
            }

            $logs = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'data' => $logs,
                'count' => $logs->count(),
                'timestamp' => now()->toIso8601String()
            ], 200, [], JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
