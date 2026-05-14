<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\AssessmentPeriod;
use App\Models\AssessmentResult;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $periods = AssessmentPeriod::whereIn('status', ['active', 'closed'])->orderByDesc('period_id')->get();
        $selectedPeriodId = $request->get('period_id', $periods->first()?->period_id);
        $selectedDepartmentId = $request->get('department_id');

        $departments = Department::orderBy('department_name')->get();

        $results = [];
        if ($selectedPeriodId) {
            $results = AssessmentResult::with('employee.department')
                ->where('period_id', $selectedPeriodId)
                ->when($selectedDepartmentId, fn($q) => $q->whereHas('employee', fn($q) => $q->where('department_id', $selectedDepartmentId)))
                ->paginate(15)->withQueryString();
        }

        return view('management.reports.index', compact('periods', 'results', 'selectedPeriodId', 'departments', 'selectedDepartmentId'));
    }

    public function show(AssessmentResult $result): View
    {
        $result->load(['employee.department', 'employee.supervisor', 'period']);
        
        // Fetch detailed assessments to show breakdown
        $assignments = $result->employee->assignmentsAsRatee()
            ->where('period_id', $result->period_id)
            ->where('is_completed', true)
            ->with(['assessments.indicator.akhlaqValue', 'rater'])
            ->get();

        // View Employee Performance Trend (All periods for this employee)
        $trend = AssessmentResult::with('period')
            ->where('employee_id', $result->employee_id)
            ->whereNotNull('final_score')
            ->orderBy('period_id', 'asc')
            ->get();

        return view('management.reports.show', compact('result', 'assignments', 'trend'));
    }

    public function exportCsv(Request $request)
    {
        $periodId = $request->get('period_id');
        $departmentId = $request->get('department_id');

        if (!$periodId) {
            return back()->with('error', 'Pilih periode penilaian terlebih dahulu.');
        }

        $results = AssessmentResult::with('employee.department')
            ->where('period_id', $periodId)
            ->when($departmentId, fn($q) => $q->whereHas('employee', fn($q) => $q->where('department_id', $departmentId)))
            ->get();

        $period = AssessmentPeriod::find($periodId);
        $filename = "assessment_report_{$period->period_name}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['NIK', 'Nama Karyawan', 'Departemen', 'Skor Self', 'Skor Peer', 'Skor Superior', 'Skor Subordinate', 'Skor Akhir', 'Gap'];

        $callback = function() use($results, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->employee->employee_number,
                    $result->employee->employee_name,
                    $result->employee->department->department_name ?? '-',
                    $result->self_score,
                    $result->peer_score,
                    $result->superior_score,
                    $result->subordinate_score,
                    $result->final_score,
                    $result->gap_score,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function gapAnalysis(Request $request): View
    {
        $periods = AssessmentPeriod::whereIn('status', ['active', 'closed'])->orderByDesc('period_id')->get();
        $selectedPeriodId = $request->get('period_id', $periods->first()?->period_id);
        
        $results = [];
        if ($selectedPeriodId) {
            $results = AssessmentResult::with('employee.department')
                ->where('period_id', $selectedPeriodId)
                ->orderBy('final_score', 'asc') // Show those with biggest gaps/lowest scores first
                ->take(10)
                ->get();
        }

        return view('management.reports.gap-analysis', compact('periods', 'results', 'selectedPeriodId'));
    }

    public function trendAnalysis(Request $request): View
    {
        $departments = Department::all();
        $selectedDeptId = $request->get('department_id');

        $periods = AssessmentPeriod::orderBy('start_date')->get();
        
        $trendData = [];
        foreach ($periods as $period) {
            $avg = AssessmentResult::where('period_id', $period->period_id)
                ->when($selectedDeptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $selectedDeptId)))
                ->avg('final_score');
            
            $trendData[] = [
                'period' => $period->period_name,
                'average' => $avg ? round($avg, 2) : 0
            ];
        }

        return view('management.reports.trend', compact('departments', 'selectedDeptId', 'trendData'));
    }
}
