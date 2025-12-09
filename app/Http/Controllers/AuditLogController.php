<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $query = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
            ->select(
                'audit_logs.id',
                'audit_logs.action',
                'audit_logs.details',
                'audit_logs.ip_address',
                'audit_logs.created_at',
                'users.username as user_name'
            )
            ->orderByDesc('audit_logs.created_at');

        // Optional search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.username', 'like', "%{$search}%")
                  ->orWhere('audit_logs.action', 'like', "%{$search}%")
                  ->orWhere('audit_logs.details', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);

        return Inertia::render('PayToPark/admin_logs', [
            'logs' => $logs,
            'admin' => $admin
        ]);
    }
}
