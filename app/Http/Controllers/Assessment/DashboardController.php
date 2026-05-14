<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            abort(403, 'Akun Anda tidak tertaut dengan data karyawan. Tidak dapat mengakses penilaian.');
        }

        // Get latest assessment result for this employee
        $latestResult = \App\Models\AssessmentResult::where('employee_id', $employee->employee_id)
            ->with('period')
            ->latest('period_id')
            ->first();

        // Count pending tasks
        $pendingCount = Assignment::where('rater_id', $employee->employee_id)
            ->where('is_completed', false)
            ->whereHas('period', fn($q) => $q->where('status', 'active'))
            ->count();

        // Get recent activities (feedbacks or completions)
        $recentTasks = Assignment::with(['ratee', 'period'])
            ->where('rater_id', $employee->employee_id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('assessment.index', compact('employee', 'latestResult', 'pendingCount', 'recentTasks'));
    }

    public function tasks(): View
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            abort(403, 'Akun Anda tidak tertaut dengan data karyawan.');
        }

        // Get pending assignments for the logged-in employee
        $pendingAssignments = Assignment::with(['ratee', 'period'])
            ->where('rater_id', $employee->employee_id)
            ->where('is_completed', false)
            ->whereHas('period', fn($q) => $q->where('status', 'active'))
            ->get();

        // Get completed assignments for history
        $completedAssignments = Assignment::with(['ratee', 'period'])
            ->where('rater_id', $employee->employee_id)
            ->where('is_completed', true)
            ->latest('completed_at')
            ->take(15)
            ->get();

        return view('assessment.dashboard', compact('pendingAssignments', 'completedAssignments'));
    }
}
