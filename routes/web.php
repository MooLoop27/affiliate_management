<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SingaporePartnerController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CommissionDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data - Singapore Partners (Admin+)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('singapore-partners', SingaporePartnerController::class);
        Route::get('/api/singapore-partners/search', [SingaporePartnerController::class, 'search'])->name('api.singapore-partners.search');

        Route::resource('leaders', LeaderController::class);
        Route::get('/api/leaders/search', [LeaderController::class, 'search'])->name('api.leaders.search');

        Route::resource('recipients', RecipientController::class);
        Route::get('/api/recipients/search', [RecipientController::class, 'search'])->name('api.recipients.search');

        // Transactions (Admin+)
        Route::resource('transactions', TransactionController::class);

        // Settings (Admin+)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // User Management (Owner only)
        Route::middleware(['role:owner'])->group(function () {
            Route::resource('user-management', UserManagementController::class);
        });

        // Activity Logs (Admin+)
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Finance routes
    Route::middleware(['role:finance'])->group(function () {
        // Payment management
        Route::post('/commission-details/{commissionDetail}/payment', [CommissionDetailController::class, 'updatePayment'])
            ->name('commission-details.update-payment');
        Route::get('/commission-details/{commissionDetail}/history', [CommissionDetailController::class, 'getPaymentHistory'])
            ->name('commission-details.history');
        Route::get('/commission-details/{commissionDetail}/download-proof', [CommissionDetailController::class, 'downloadProof'])
            ->name('commission-details.download-proof');
    });

    // Reports (Finance+)
    Route::middleware(['role:admin,finance'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });
});

require __DIR__.'/auth.php';

