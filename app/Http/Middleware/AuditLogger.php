<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogger
{
    /**
     * Log every authenticated request automatically.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && $request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            AuditLog::record(
                activity: strtoupper($request->method()) . ' ' . $request->path(),
                tableName: null,
                recordId: null,
            );
        }

        return $response;
    }
}
