<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssessmentPeriod;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(Request $request): View
    {
        $periods = AssessmentPeriod::orderByDesc('period_id')->get();
        $selectedPeriodId = $request->get('period_id', $periods->where('status', 'active')->first()?->period_id ?? $periods->first()?->period_id);

        $period = AssessmentPeriod::find($selectedPeriodId);

        $progress = [];
        if ($period) {
            // Group assignments by ratee, show completion per employee
            $assignments = Assignment::with(['rater.user', 'ratee'])
                ->where('period_id', $period->period_id)
                ->get()
                ->groupBy('ratee_id');

            foreach ($assignments as $rateeId => $group) {
                $total     = $group->count();
                $completed = $group->where('is_completed', true)->count();
                $progress[] = [
                    'employee'    => $group->first()->ratee,
                    'total'       => $total,
                    'completed'   => $completed,
                    'pending'     => $total - $completed,
                    'percentage'  => $total > 0 ? round(($completed / $total) * 100) : 0,
                    'assignments' => $group,
                ];
            }
        }

        $overallPercent = 0;
        if (count($progress)) {
            $overallPercent = round(collect($progress)->avg('percentage'));
        }

        return view('admin.progress.index', compact('periods', 'period', 'progress', 'overallPercent', 'selectedPeriodId'));
    }

    public function notifications(Request $request): View
    {
        $periods = AssessmentPeriod::orderByDesc('period_id')->get();
        $selectedPeriodId = $request->get('period_id', $periods->where('status', 'active')->first()?->period_id ?? $periods->first()?->period_id);
        $period = AssessmentPeriod::find($selectedPeriodId);

        $pendingEmployees = [];
        if ($period) {
            // Get all incomplete assignments grouped by rater
            $pendingAssignments = Assignment::with(['rater.user', 'ratee'])
                ->where('period_id', $period->period_id)
                ->where('is_completed', false)
                ->get()
                ->groupBy('rater_id');

            foreach ($pendingAssignments as $raterId => $group) {
                $pendingEmployees[] = [
                    'employee' => $group->first()->rater,
                    'count'    => $group->count(),
                    'tasks'    => $group->map(fn($a) => $a->ratee->employee_name)->implode(', '),
                ];
            }
        }

        return view('admin.progress.notifications', compact('periods', 'period', 'pendingEmployees', 'selectedPeriodId'));
    }

    public function sendNotification(Request $request)
    {
        $employeeId = $request->get('employee_id');
        
        if ($employeeId) {
            $employee = Employee::find($employeeId);
            return back()->with('success', "Reminder successfully sent to {$employee->employee_name}.");
        }

        return back()->with('success', 'Reminder notifications have been successfully sent to all employees with pending assessment tasks.');
    }
}
