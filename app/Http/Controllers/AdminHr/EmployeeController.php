<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Employee::with(['department', 'supervisor'])
            ->when($request->filled('search'), fn($q) => $q->where('employee_name', 'like', '%' . $request->search . '%')
                ->orWhere('employee_number', 'like', '%' . $request->search . '%'))
            ->when($request->filled('department_id'), fn($q) => $q->where('department_id', $request->department_id))
            ->paginate(15)->withQueryString();

        $departments = Department::orderBy('department_name')->get();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('department_name')->get();
        $supervisors = Employee::orderBy('employee_name')->get();
        return view('admin.employees.create', compact('departments', 'supervisors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_name'   => ['required', 'string', 'max:150'],
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'department_id'   => ['required', 'exists:departments,department_id'],
            'supervisor_id'   => ['nullable', 'exists:employees,employee_id'],
        ]);

        $employee = Employee::create($validated);
        AuditLog::record('CREATE employee', 'employees', $employee->employee_id, [], $employee->toArray());

        return redirect()->route('admin.employees.index')->with('success', 'Employee data added successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'supervisor', 'subordinates', 'user.role', 'assessmentResults.period']);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::orderBy('department_name')->get();
        $supervisors = Employee::where('employee_id', '!=', $employee->employee_id)->orderBy('employee_name')->get();
        return view('admin.employees.edit', compact('employee', 'departments', 'supervisors'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_name'   => ['required', 'string', 'max:150'],
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number,' . $employee->employee_id . ',employee_id'],
            'department_id'   => ['required', 'exists:departments,department_id'],
            'supervisor_id'   => ['nullable', 'exists:employees,employee_id'],
        ]);

        $old = $employee->toArray();
        $employee->update($validated);
        AuditLog::record('UPDATE employee', 'employees', $employee->employee_id, $old, $employee->fresh()->toArray());

        return redirect()->route('admin.employees.index')->with('success', 'Employee data updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->user()->exists()) {
            return back()->with('error', 'This employee has an active user account. Please delete the user account first.');
        }

        AuditLog::record('DELETE employee', 'employees', $employee->employee_id, $employee->toArray(), []);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee data deleted successfully.');
    }
}
