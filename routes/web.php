<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminHr;
use App\Http\Controllers\Management;
use App\Http\Controllers\Assessment;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role->role_slug ?? '';
        return match($role) {
            'admin_hr'  => redirect()->route('admin.dashboard'),
            'manajemen' => redirect()->route('management.dashboard'),
            'atasan', 'karyawan' => redirect()->route('assessment.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// All authenticated routes wrapped in audit logger
Route::middleware(['auth', \App\Http\Middleware\AuditLogger::class])->group(function () {
    
    // ADMIN HR ROUTES
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class.':admin_hr')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminHr\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('periods', AdminHr\AssessmentPeriodController::class);
        
        Route::get('assignments/bulk-create', [AdminHr\AssignmentController::class, 'bulkCreate'])->name('assignments.bulk-create');
        Route::post('assignments/bulk-store', [AdminHr\AssignmentController::class, 'bulkStore'])->name('assignments.bulk-store');
        Route::resource('assignments', AdminHr\AssignmentController::class)->except(['show', 'edit', 'update']);
        
        Route::resource('employees', AdminHr\EmployeeController::class);
        Route::resource('departments', AdminHr\DepartmentController::class)->except(['show']);
        Route::resource('akhlaq-values', AdminHr\AkhlaqValueController::class);
        
        Route::resource('users', AdminHr\UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle-status', [AdminHr\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        Route::get('progress', [AdminHr\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/notifications', [AdminHr\ProgressController::class, 'notifications'])->name('progress.notifications');
        Route::post('progress/send-notification', [AdminHr\ProgressController::class, 'sendNotification'])->name('progress.send-notification');
        Route::get('audit-logs', [AdminHr\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // MANAGEMENT & ADMIN HR ROUTES (Keduanya bisa melihat Laporan)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class.':manajemen,admin_hr')->prefix('management')->name('management.')->group(function () {
        Route::get('/dashboard', [Management\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/reports', [Management\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/gap-analysis', [Management\ReportController::class, 'gapAnalysis'])->name('reports.gap-analysis');
        Route::get('/reports/performance-trend', [Management\ReportController::class, 'trendAnalysis'])->name('reports.trend');
        Route::get('/reports/{result}', [Management\ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports-export', [Management\ReportController::class, 'exportCsv'])->name('reports.export');
    });

    // ASSESSMENT ROUTES (Semua role bisa mengakses karena setiap orang dinilai)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class.':admin_hr,manajemen,atasan,karyawan')->prefix('assessment')->name('assessment.')->group(function () {
        Route::get('/dashboard', [Assessment\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tasks', [Assessment\DashboardController::class, 'tasks'])->name('tasks');
        
        Route::get('/form/{assignment}', [Assessment\FormController::class, 'show'])->name('form.show');
        Route::post('/form/{assignment}', [Assessment\FormController::class, 'store'])->name('form.store');
    });
});
