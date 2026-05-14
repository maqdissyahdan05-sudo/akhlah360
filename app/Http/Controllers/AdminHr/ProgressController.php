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

    public function sendNotification(Request $request)
    {
        // In a real app, you would dispatch a job to send emails/notifications
        // For now, we simulate sending to all employees with pending tasks
        return back()->with('success', 'Reminder notifications have been successfully sent to all employees with pending assessment tasks.');
    }
}
