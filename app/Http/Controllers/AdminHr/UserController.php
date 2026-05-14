<?php

namespace App\Http\Controllers\AdminHr;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with(['role', 'employee.department'])
            ->when($request->filled('search'), fn($q) => $q->where('username', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->when($request->filled('role_id'), fn($q) => $q->where('role_id', $request->role_id))
            ->paginate(15)->withQueryString();

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles     = Role::all();
        $employees = Employee::doesntHave('user')->orderBy('employee_name')->get();
        return view('admin.users.create', compact('roles', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role_id'     => ['required', 'exists:roles,role_id'],
            'employee_id' => ['nullable', 'exists:employees,employee_id', 'unique:users,employee_id'],
            'username'    => ['required', 'string', 'max:100', 'unique:users,username'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'is_active'   => ['boolean'],
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);
        AuditLog::record('CREATE user', 'users', $user->user_id, [], collect($user->toArray())->except('password')->all());

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user): View
    {
        $roles     = Role::all();
        $employees = Employee::where(fn($q) => $q->doesntHave('user')->orWhere('employee_id', $user->employee_id))
            ->orderBy('employee_name')->get();
        return view('admin.users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id'     => ['required', 'exists:roles,role_id'],
            'employee_id' => ['nullable', 'exists:employees,employee_id', 'unique:users,employee_id,' . $user->user_id . ',user_id'],
            'username'    => ['required', 'string', 'max:100', 'unique:users,username,' . $user->user_id . ',user_id'],
            'email'       => ['required', 'email', 'unique:users,email,' . $user->user_id . ',user_id'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active'   => ['boolean'],
        ]);

        $old = collect($user->toArray())->except('password')->all();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);
        AuditLog::record('UPDATE user', 'users', $user->user_id, $old, collect($user->fresh()->toArray())->except('password')->all());

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->user_id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        AuditLog::record('DELETE user', 'users', $user->user_id, collect($user->toArray())->except('password')->all(), []);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);
        $statusLabel = $user->is_active ? 'activated' : 'deactivated';
        AuditLog::record("TOGGLE STATUS user [{$statusLabel}]", 'users', $user->user_id);

        return back()->with('success', "Account has been successfully {$statusLabel}.");
    }
}
