<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('employees')->orderBy('department_name')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100', 'unique:departments,department_name'],
        ]);

        $dept = Department::create($validated);
        AuditLog::record('CREATE department', 'departments', $dept->department_id, [], $dept->toArray());

        return redirect()->route('admin.departments.index')->with('success', 'Department added successfully.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100', 'unique:departments,department_name,' . $department->department_id . ',department_id'],
        ]);

        $old = $department->toArray();
        $department->update($validated);
        AuditLog::record('UPDATE department', 'departments', $department->department_id, $old, $department->fresh()->toArray());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Cannot delete a department that still has employees assigned to it.');
        }

        AuditLog::record('DELETE department', 'departments', $department->department_id, $department->toArray(), []);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
