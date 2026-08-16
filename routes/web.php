<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Quick Role Switcher (For Demo & Testing)
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('quick-login');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (All Auth)
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Surat Masuk (Hanya Admin yang bisa CRUD)
    Route::middleware('role:admin')->group(function () {
        Route::resource('letters', LetterController::class)->except(['index', 'show']);
    });
    
    // Semua auth user bisa melihat surat (untuk list dan detail surat disposisi)
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/{letter}', [LetterController::class, 'show'])->name('letters.show');
    Route::get('/letters/{letter}/print-agenda', [LetterController::class, 'printAgenda'])->name('letters.print-agenda');

    // Disposisi (All Auth - tapi nanti dikontrol di controller view)
    Route::get('/dispositions', [DispositionController::class, 'index'])->name('dispositions.index');
    
    // Hanya Admin & Pimpinan yang bisa buat disposisi
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::post('/dispositions', [DispositionController::class, 'store'])->name('dispositions.store');
    });

    Route::put('/dispositions/{disposition}/follow-up', [DispositionController::class, 'followUp'])->name('dispositions.follow-up');
    Route::get('/dispositions/{letter}/print-sheet', [DispositionController::class, 'printSheet'])->name('dispositions.print-sheet');

    // Arsip Surat (All Auth)
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');

    // Laporan (Admin & Pimpinan)
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
        Route::get('/reports/print', [ReportController::class, 'printReport'])->name('reports.print');
    });

    // Audit Log (Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // Master Data (Admin)
    Route::middleware('role:admin')->prefix('master')->name('master.')->group(function () {
        Route::get('/users', [MasterDataController::class, 'users'])->name('users');
        Route::post('/users', [MasterDataController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [MasterDataController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [MasterDataController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/categories', [MasterDataController::class, 'categories'])->name('categories');
        Route::post('/categories', [MasterDataController::class, 'storeCategory'])->name('categories.store');
    });
});
