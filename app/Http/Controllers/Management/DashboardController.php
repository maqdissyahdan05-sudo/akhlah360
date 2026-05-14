<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $activePeriod = AssessmentPeriod::where('status', 'active')->latest()->first();
        $latestClosedPeriod = AssessmentPeriod::where('status', 'closed')->latest()->first();

        // Stats card
        $stats = [
            'total_employees'  => Employee::count(),
            'total_departments'=> Department::count(),
            'active_period'    => $activePeriod?->period_name ?? '-',
            'avg_final_score'  => $latestClosedPeriod
                ? round(AssessmentResult::where('period_id', $latestClosedPeriod->period_id)->avg('final_score'), 2)
                : 0,
        ];

        // Top performers from latest closed period
        $topPerformers = [];
        if ($latestClosedPeriod) {
            $topPerformers = AssessmentResult::with('employee.department')
                ->where('period_id', $latestClosedPeriod->period_id)
                ->orderByDesc('final_score')
                ->take(5)
                ->get();
        }

        // Department score averages for chart
        $deptScores = [];
        if ($latestClosedPeriod) {
            $deptScores = AssessmentResult::with('employee.department')
                ->where('period_id', $latestClosedPeriod->period_id)
                ->get()
                ->groupBy(fn($r) => $r->employee->department->department_name ?? 'N/A')
                ->map(fn($group) => round($group->avg('final_score'), 2));
        }

        // Historical trend: avg final score per closed period
        $trend = AssessmentPeriod::where('status', 'closed')
            ->orderBy('end_date')
            ->get()
            ->map(fn($p) => [
                'period' => $p->period_name,
                'avg'    => round(AssessmentResult::where('period_id', $p->period_id)->avg('final_score') ?? 0, 2),
            ]);

        return view('management.dashboard', compact('stats', 'topPerformers', 'deptScores', 'trend', 'latestClosedPeriod'));
    }
}
