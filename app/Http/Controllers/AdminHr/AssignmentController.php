<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Assignment;
use App\Models\AssessmentPeriod;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $periods = AssessmentPeriod::orderByDesc('period_id')->get();
        $selectedPeriodId = $request->get('period_id', $periods->first()?->period_id);

        $assignments = Assignment::with(['rater', 'ratee', 'period'])
            ->when($selectedPeriodId, fn($q) => $q->where('period_id', $selectedPeriodId))
            ->paginate(15);

        return view('admin.assignments.index', compact('periods', 'assignments', 'selectedPeriodId'));
    }

    public function create(): View
    {
        $periods    = AssessmentPeriod::where('status', 'draft')->get();
        $employees  = Employee::with('department')->orderBy('employee_name')->get();
        return view('admin.assignments.create', compact('periods', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period_id'         => ['required', 'exists:assessment_periods,period_id'],
            'rater_id'          => ['required', 'exists:employees,employee_id'],
            'ratee_id'          => ['required', 'exists:employees,employee_id'],
            'relationship_type' => ['required', 'in:self,peer,superior,subordinate'],
        ]);

        // Validate: self-assessment must have rater == ratee
        if ($validated['relationship_type'] === 'self' && $validated['rater_id'] != $validated['ratee_id']) {
            return back()->withErrors(['relationship_type' => 'Self-assessment must have the same rater and ratee.'])->withInput();
        }

        $assignment = Assignment::firstOrCreate(
            [
                'period_id'         => $validated['period_id'],
                'rater_id'          => $validated['rater_id'],
                'ratee_id'          => $validated['ratee_id'],
                'relationship_type' => $validated['relationship_type'],
            ]
        );

        AuditLog::record('CREATE assignment', 'assignments', $assignment->assignment_id, [], $assignment->toArray());

        return redirect()->route('admin.assignments.index')->with('success', '360° assessment assignment saved successfully.');
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period_id'          => ['required', 'exists:assessment_periods,period_id'],
            'ratee_id'           => ['required', 'exists:employees,employee_id'],
            'peer_ids'           => ['nullable', 'array'],
            'peer_ids.*'         => ['exists:employees,employee_id'],
            'superior_ids'       => ['nullable', 'array'],
            'superior_ids.*'     => ['exists:employees,employee_id'],
            'subordinate_ids'    => ['nullable', 'array'],
            'subordinate_ids.*'  => ['exists:employees,employee_id'],
        ]);

        $periodId = $validated['period_id'];
        $rateeId  = $validated['ratee_id'];

        // Create self-assessment assignment
        Assignment::firstOrCreate([
            'period_id' => $periodId, 'rater_id' => $rateeId, 'ratee_id' => $rateeId, 'relationship_type' => 'self',
        ]);

        // Create peer assignments
        foreach (($validated['peer_ids'] ?? []) as $peerId) {
            Assignment::firstOrCreate([
                'period_id' => $periodId, 'rater_id' => $peerId, 'ratee_id' => $rateeId, 'relationship_type' => 'peer',
            ]);
        }

        // Create superior assignments
        foreach (($validated['superior_ids'] ?? []) as $supId) {
            Assignment::firstOrCreate([
                'period_id' => $periodId, 'rater_id' => $supId, 'ratee_id' => $rateeId, 'relationship_type' => 'superior',
            ]);
        }

        // Create subordinate assignments
        foreach (($validated['subordinate_ids'] ?? []) as $subId) {
            Assignment::firstOrCreate([
                'period_id' => $periodId, 'rater_id' => $subId, 'ratee_id' => $rateeId, 'relationship_type' => 'subordinate',
            ]);
        }

        AuditLog::record('BULK CREATE assignments', 'assignments', null, [], $validated);

        return redirect()->route('admin.assignments.index', ['period_id' => $periodId])->with('success', '360° rater mapping saved successfully.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        if ($assignment->is_completed) {
            return back()->with('error', 'Cannot delete an assignment that has already been completed.');
        }

        AuditLog::record('DELETE assignment', 'assignments', $assignment->assignment_id, $assignment->toArray(), []);
        $assignment->delete();

        return back()->with('success', 'Assignment deleted successfully.');
    }

    public function bulkCreate(): View
    {
        $periods   = AssessmentPeriod::where('status', 'draft')->get();
        $employees = Employee::with('department', 'supervisor', 'subordinates')->orderBy('employee_name')->get();
        return view('admin.assignments.bulk-create', compact('periods', 'employees'));
    }
}
