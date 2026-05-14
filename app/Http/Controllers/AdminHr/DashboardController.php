<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\Assignment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_employees'    => Employee::count(),
            'total_users'        => User::count(),
            'total_departments'  => Department::count(),
            'active_periods'     => AssessmentPeriod::where('status', 'active')->count(),
        ];

        $activePeriod = AssessmentPeriod::where('status', 'active')->latest()->first();

        $progressData = null;
        if ($activePeriod) {
            $total     = $activePeriod->total_assignments;
            $completed = $activePeriod->completed_assignments;
            $progressData = [
                'period'    => $activePeriod,
                'total'     => $total,
                'completed' => $completed,
                'percent'   => $total > 0 ? round(($completed / $total) * 100) : 0,
            ];
        }

        $recentPeriods = AssessmentPeriod::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'progressData', 'recentPeriods'));
    }
}
