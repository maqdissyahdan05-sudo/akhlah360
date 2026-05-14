<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::with('user')
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('table_name'), fn($q) => $q->where('table_name', $request->table_name))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('timestamp', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('timestamp', '<=', $request->date_to))
            ->orderByDesc('timestamp')
            ->paginate(20)
            ->withQueryString();

        $tables = AuditLog::distinct()->pluck('table_name')->filter()->sort()->values();

        return view('admin.audit-logs.index', compact('logs', 'tables'));
    }
}
