<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Support login with username OR email
        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'The provided credentials do not match our records.'])->withInput($request->only('username'));
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['username' => 'Your account has been deactivated. Please contact HR Admin.'])->withInput($request->only('username'));
        }

        $request->session()->regenerate();

        AuditLog::record('LOGIN', 'users', $user->user_id);

        // Redirect based on role
        return match ($user->role->role_slug) {
            'admin_hr'  => redirect()->route('admin.dashboard'),
            'manajemen' => redirect()->route('management.dashboard'),
            'atasan'    => redirect()->route('assessment.dashboard'),
            'karyawan'  => redirect()->route('assessment.dashboard'),
            default     => redirect('/'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        AuditLog::record('LOGOUT', 'users', auth()->id());

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
