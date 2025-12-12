<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');

// Authentication routes
Route::middleware('guest')->group(function () {
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Protected routes - allow both admin and user guards
Route::middleware('check.auth')->group(function () {
    // Dark mode toggle
    Route::post('/toggle-dark-mode', [AuthController::class, 'toggleDarkMode'])->name('toggle-dark-mode');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/completed', [ReportController::class, 'completed'])->name('reports.completed');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::post('/reports/{report}/complete', [ReportController::class, 'complete'])->name('reports.complete');
    Route::get('/reports/{report}/export', [ReportController::class, 'exportPdf'])->name('reports.export');
    Route::post('/reports/{report}/send-email', [ReportController::class, 'sendEmail'])->name('reports.send-email');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/technicians/create', [AdminController::class, 'createTechnician'])->name('technicians.create');
        Route::post('/technicians', [AdminController::class, 'storeTechnician'])->name('technicians.store');
        Route::get('/users/monitoring', [AdminController::class, 'userMonitoring'])->name('users.monitoring');
        Route::get('/technicians/{user}/reports', [AdminController::class, 'technicianReports'])->name('technicians.reports');
    });
});

