<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============================================
// PUBLIC ROUTES (Guest Only)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Manajemen User
        Route::get('/manajemen-user', [App\Http\Controllers\Admin\ManajemenUserController::class, 'index'])
            ->name('manajemen-user');
        Route::post('/manajemen-user/{id}/toggle', [App\Http\Controllers\Admin\ManajemenUserController::class, 'toggleStatus'])
            ->name('manajemen-user.toggle');
        Route::delete('/manajemen-user/{id}', [App\Http\Controllers\Admin\ManajemenUserController::class, 'destroy'])
            ->name('manajemen-user.destroy');
        
        // Monitoring Data
        Route::get('/monitoring-nasabah', [App\Http\Controllers\Admin\MonitoringController::class, 'nasabah'])
            ->name('monitoring.nasabah');
        Route::get('/monitoring-surat', [App\Http\Controllers\Admin\MonitoringController::class, 'surat'])
            ->name('monitoring.surat');
        Route::get('/monitoring-lelang', [App\Http\Controllers\Admin\MonitoringController::class, 'lelang'])
            ->name('monitoring.lelang');
        
        // Pengajuan Lelang Actions
        Route::post('/monitoring-lelang/{id}/approve', [App\Http\Controllers\Admin\MonitoringController::class, 'approveLelang'])
            ->name('monitoring.lelang.approve');
        Route::post('/monitoring-lelang/{id}/reject', [App\Http\Controllers\Admin\MonitoringController::class, 'rejectLelang'])
            ->name('monitoring.lelang.reject');
            
        Route::get('/nasabah/{id}/dokumen', [App\Http\Controllers\Admin\NasabahController::class, 'showDokumen'])
             ->name('nasabah.dokumen');

        Route::get('/monitoring-trr', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'index'])
            ->name('monitoring.trr');
        Route::post('/monitoring-trr/{id}/approve', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'approve'])
            ->name('monitoring.trr.approve');
        Route::post('/monitoring-trr/{id}/reject', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'reject'])
            ->name('monitoring.trr.reject');
        Route::get('/monitoring-trr/{id}/detail', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'detail'])
            ->name('monitoring.trr.detail');

        Route::post('/monitoring-trr/{id}/hasil', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'storeHasil'])
            ->name('monitoring.trr.hasil.store');
        
        // Export rekap hasil lelang ke CSV
        Route::get('/monitoring-trr/export-hasil', [App\Http\Controllers\Admin\MonitoringTrrController::class, 'exportHasil'])
            ->name('monitoring.trr.export');
    });

// ============================================
// PETUGAS ROUTES (Requires: auth + role:petugas)
// ============================================
Route::prefix('petugas')
    ->middleware(['auth', 'role:petugas'])
    ->name('petugas.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Data Nasabah
        Route::get('/data-nasabah', [App\Http\Controllers\Petugas\NasabahController::class, 'index'])
            ->name('nasabah.index');
        Route::post('/data-nasabah', [App\Http\Controllers\Petugas\NasabahController::class, 'store'])
            ->name('nasabah.store');
        Route::get('/nasabah/{id}/dokumen', [App\Http\Controllers\Petugas\NasabahController::class, 'showDokumen'])
            ->name('nasabah.dokumen');
        
        // Upload Dokumen
        Route::get('/upload-dokumen', [App\Http\Controllers\Petugas\DokumenController::class, 'listNasabah'])
            ->name('dokumen.list');
        Route::get('/upload-dokumen/{id}', [App\Http\Controllers\Petugas\DokumenController::class, 'form'])
            ->name('dokumen.form');
        Route::post('/upload-dokumen/{id}', [App\Http\Controllers\Petugas\DokumenController::class, 'store'])
            ->name('dokumen.store');
        
        // Nomor Surat - Main Page
        Route::get('/nomor-surat', [App\Http\Controllers\Petugas\SuratController::class, 'index'])
            ->name('nomor-surat');
        
        // Surat Keluar
        Route::get('/surat-keluar', [App\Http\Controllers\Petugas\SuratController::class, 'suratKeluar'])
            ->name('surat.keluar.index');
        Route::post('/surat-keluar', [App\Http\Controllers\Petugas\SuratController::class, 'storeSuratKeluar'])
            ->name('surat.keluar.store');
        
        // Memo
        Route::get('/memo', [App\Http\Controllers\Petugas\SuratController::class, 'memo'])
            ->name('surat.memo.index');
        Route::post('/memo', [App\Http\Controllers\Petugas\SuratController::class, 'storeMemo'])
            ->name('surat.memo.store');
        
        // Nota
        Route::get('/nota', [App\Http\Controllers\Petugas\SuratController::class, 'nota'])
            ->name('surat.nota.index');
        Route::post('/nota', [App\Http\Controllers\Petugas\SuratController::class, 'storeNota'])
            ->name('surat.nota.store');
        
        // LPA (Laporan Penilaian Agunan)
        Route::get('/lpa', [App\Http\Controllers\Petugas\LpaController::class, 'index'])
        ->name('lpa.index');
        Route::post('/lpa', [App\Http\Controllers\Petugas\LpaController::class, 'store'])
        ->name('lpa.store');
        
        // Pengajuan Lelang
        Route::get('/pengajuan-lelang', [App\Http\Controllers\Petugas\PengajuanLelangController::class, 'index'])
            ->name('pengajuan-lelang.index');
        Route::post('/pengajuan-lelang', [App\Http\Controllers\Petugas\PengajuanLelangController::class, 'store'])
            ->name('pengajuan-lelang.store');

         Route::get('/dana-trr', [App\Http\Controllers\Petugas\DanaTrrController::class, 'index'])
            ->name('dana-trr.index');

        // Konfirmasi penerimaan dana
        Route::post('/dana-trr/{id}/konfirmasi', [App\Http\Controllers\Petugas\DanaTrrController::class, 'konfirmasi'])
            ->name('dana-trr.konfirmasi');

        // Halaman buku kas (ledger) satu TRR tertentu
        Route::get('/dana-trr/{id}/ledger', [App\Http\Controllers\Petugas\DanaTrrController::class, 'ledger'])
            ->name('dana-trr.ledger');

        // Simpan satu baris pengeluaran baru ke ledger
        Route::post('/dana-trr/{id}/ledger', [App\Http\Controllers\Petugas\DanaTrrController::class, 'storeLedger'])
            ->name('dana-trr.ledger.store');

        // Tandai lelang selesai / tutup buku kas
        Route::post('/dana-trr/{id}/selesai', [App\Http\Controllers\Petugas\DanaTrrController::class, 'selesai'])
            ->name('dana-trr.selesai');

        // Export PDF ledger
        Route::get('/dana-trr/{id}/export-pdf', [App\Http\Controllers\Petugas\DanaTrrController::class, 'exportPdf'])
            ->name('dana-trr.export-pdf');

        Route::post('/pengajuan-trr', [App\Http\Controllers\Petugas\PengajuanTrrController::class, 'store'])
            ->name('pengajuan-trr.store');
    });