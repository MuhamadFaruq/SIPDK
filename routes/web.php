<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OutgoingLetterController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Quick Role Switcher (For Demo & Testing)
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->middleware('throttle:10,1')->name('quick-login');

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
    
    // Surat Masuk List, Show, File Stream & Agenda
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/{letter}', [LetterController::class, 'show'])->name('letters.show');
    Route::get('/letters/{letter}/file', [LetterController::class, 'file'])->name('letters.file');
    Route::get('/letters/{letter}/print-agenda', [LetterController::class, 'printAgenda'])->name('letters.print-agenda');

    // Surat Keluar (Hanya Admin yang bisa CRUD)
    Route::middleware('role:admin')->group(function () {
        Route::resource('outgoing-letters', OutgoingLetterController::class)->except(['index', 'show']);
    });
    Route::get('/outgoing-letters', [OutgoingLetterController::class, 'index'])->name('outgoing-letters.index');
    Route::get('/outgoing-letters/{outgoing_letter}', [OutgoingLetterController::class, 'show'])->name('outgoing-letters.show');
    Route::get('/outgoing-letters/{outgoing_letter}/file', [OutgoingLetterController::class, 'file'])->name('outgoing-letters.file');
    Route::get('/outgoing-letters/{outgoing_letter}/print-agenda', [OutgoingLetterController::class, 'printAgenda'])->name('outgoing-letters.print-agenda');

    // Disposisi (All Auth - dikontrol di controller view)
    Route::get('/dispositions', [DispositionController::class, 'index'])->name('dispositions.index');
    
    // Hanya Admin & Pimpinan yang bisa buat disposisi awal
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::post('/dispositions', [DispositionController::class, 'store'])->name('dispositions.store');
    });

    // Teruskan Disposisi (Cascading / Forwarding)
    Route::post('/dispositions/{disposition}/forward', [DispositionController::class, 'forward'])->name('dispositions.forward');
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
