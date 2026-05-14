<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\AssessmentPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentPeriodController extends Controller
{
    public function index(): View
    {
        $periods = AssessmentPeriod::latest()->paginate(10);
        return view('admin.periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('admin.periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period_name' => ['required', 'string', 'max:150'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'status'      => ['required', 'in:draft,active,closed'],
        ]);

        $period = AssessmentPeriod::create($validated);

        AuditLog::record('CREATE assessment period', 'assessment_periods', $period->period_id, [], $period->toArray());

        return redirect()->route('admin.periods.index')->with('success', 'Assessment period created successfully.');
    }

    public function show(AssessmentPeriod $period): View
    {
        $period->load(['assignments.rater', 'assignments.ratee', 'assessmentResults.employee']);
        $completionData = [
            'total'     => $period->total_assignments,
            'completed' => $period->completed_assignments,
            'percent'   => $period->completion_percentage,
        ];
        return view('admin.periods.show', compact('period', 'completionData'));
    }

    public function edit(AssessmentPeriod $period): View
    {
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, AssessmentPeriod $period): RedirectResponse
    {
        $validated = $request->validate([
            'period_name' => ['required', 'string', 'max:150'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'status'      => ['required', 'in:draft,active,closed'],
        ]);

        $old = $period->toArray();
        $period->update($validated);

        AuditLog::record('UPDATE assessment period', 'assessment_periods', $period->period_id, $old, $period->fresh()->toArray());

        return redirect()->route('admin.periods.index')->with('success', 'Assessment period updated successfully.');
    }

    public function destroy(AssessmentPeriod $period): RedirectResponse
    {
        if ($period->status === 'active') {
            return back()->with('error', 'Cannot delete an active assessment period.');
        }

        AuditLog::record('DELETE assessment period', 'assessment_periods', $period->period_id, $period->toArray(), []);
        $period->delete();

        return redirect()->route('admin.periods.index')->with('success', 'Assessment period deleted successfully.');
    }
}
