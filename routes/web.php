<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SuratDomisiliTinggalController;
use App\Http\Controllers\SuratDomisiliUsahaController;
use App\Http\Controllers\SuratKematianController;
use App\Http\Controllers\SuratNikahController;
use App\Http\Controllers\SuratMandahController;
use App\Http\Controllers\SuratPenghasilanController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Debug route untuk melihat error detail
Route::get('/test-basic', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Basic test endpoint working',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
    ]);
});

Route::get('/debug-info', function () {
    return response()->json([
        'app_debug' => config('app.debug'),
        'app_env' => config('app.env'),
        'app_key' => config('app.key') ? 'SET' : 'NOT SET',
        'database_connection' => config('database.default'),
        'database_host' => config('database.connections.mysql.host'),
        'database_name' => config('database.connections.mysql.database'),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'storage_writable' => is_writable(storage_path()),
        'cache_writable' => is_writable(storage_path('framework/cache')),
        'logs_writable' => is_writable(storage_path('logs')),
    ]);
});

// QR Code verification route (public)
Route::get('/verify/{kode_permohonan}', [PermohonanController::class, 'verifyQRCode'])->name('permohonan.verify');



// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Permohonan Management for Admin
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('admin.permohonan.index');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('admin.permohonan.show');
        Route::get('/permohonan/{permohonan}/edit', [PermohonanController::class, 'edit'])->name('admin.permohonan.edit');
        Route::put('/permohonan/{permohonan}', [PermohonanController::class, 'update'])->name('admin.permohonan.update');
        Route::patch('/permohonan/{permohonan}/status', [PermohonanController::class, 'updateStatus'])->name('admin.permohonan.updateStatus');
        Route::post('/permohonan/{permohonan}/signature', [PermohonanController::class, 'addSignature'])->name('admin.permohonan.addSignature');
        Route::get('/permohonan/{permohonan}/download', [PermohonanController::class, 'download'])->name('admin.permohonan.download');
        Route::get('/permohonan/{permohonan}/dokumen/{documentId}/download', [PermohonanController::class, 'downloadDocument'])->name('admin.permohonan.downloadDocument');
        Route::delete('/permohonan/{permohonan}', [PermohonanController::class, 'destroy'])->name('admin.permohonan.destroy');
        
        // File Upload Management for Admin
        Route::post('/permohonan/{permohonan}/upload', [FileUploadController::class, 'uploadDocument'])->name('admin.permohonan.upload');
        Route::delete('/dokumen/{dokumen}', [FileUploadController::class, 'deleteDocument'])->name('admin.dokumen.delete');
        Route::get('/dokumen/{dokumen}/download', [FileUploadController::class, 'downloadDocument'])->name('admin.dokumen.download');
        Route::post('/permohonan/{permohonan}/upload-letter', [FileUploadController::class, 'uploadGeneratedLetter'])->name('admin.permohonan.upload-letter');
        
        // Letter Type Management for Admin
        Route::get('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'show'])->name('admin.surat-domisili-tinggal.show');
        Route::get('/surat-domisili-tinggal/{permohonan}/edit', [SuratDomisiliTinggalController::class, 'edit'])->name('admin.surat-domisili-tinggal.edit');
        Route::put('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'update'])->name('admin.surat-domisili-tinggal.update');
        Route::get('/surat-domisili-tinggal/{permohonan}/pdf', [SuratDomisiliTinggalController::class, 'generatePDF'])->name('admin.surat-domisili-tinggal.pdf');
        
        Route::get('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'show'])->name('admin.surat-domisili-usaha.show');
        Route::get('/surat-domisili-usaha/{permohonan}/edit', [SuratDomisiliUsahaController::class, 'edit'])->name('admin.surat-domisili-usaha.edit');
        Route::put('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'update'])->name('admin.surat-domisili-usaha.update');
        Route::get('/surat-domisili-usaha/{permohonan}/pdf', [SuratDomisiliUsahaController::class, 'generatePDF'])->name('admin.surat-domisili-usaha.pdf');
        
        Route::get('/surat-kematian/{permohonan}', [SuratKematianController::class, 'show'])->name('admin.surat-kematian.show');
        Route::get('/surat-kematian/{permohonan}/edit', [SuratKematianController::class, 'edit'])->name('admin.surat-kematian.edit');
        Route::put('/surat-kematian/{permohonan}', [SuratKematianController::class, 'update'])->name('admin.surat-kematian.update');
        Route::get('/surat-kematian/{permohonan}/pdf', [SuratKematianController::class, 'generatePDF'])->name('admin.surat-kematian.pdf');
        
        Route::get('/surat-nikah/{permohonan}', [SuratNikahController::class, 'show'])->name('admin.surat-nikah.show');
        Route::get('/surat-nikah/{permohonan}/edit', [SuratNikahController::class, 'edit'])->name('admin.surat-nikah.edit');
        Route::put('/surat-nikah/{permohonan}', [SuratNikahController::class, 'update'])->name('admin.surat-nikah.update');
        Route::get('/surat-nikah/{permohonan}/pdf', [SuratNikahController::class, 'generatePDF'])->name('admin.surat-nikah.pdf');
        
        Route::get('/surat-mandah/{permohonan}', [SuratMandahController::class, 'show'])->name('admin.surat-mandah.show');
        Route::get('/surat-mandah/{permohonan}/edit', [SuratMandahController::class, 'edit'])->name('admin.surat-mandah.edit');
        Route::put('/surat-mandah/{permohonan}', [SuratMandahController::class, 'update'])->name('admin.surat-mandah.update');
        Route::get('/surat-mandah/{permohonan}/pdf', [SuratMandahController::class, 'generatePDF'])->name('admin.surat-mandah.pdf');
        
        Route::get('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'show'])->name('admin.surat-penghasilan.show');
        Route::get('/surat-penghasilan/{permohonan}/edit', [SuratPenghasilanController::class, 'edit'])->name('admin.surat-penghasilan.edit');
        Route::put('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'update'])->name('admin.surat-penghasilan.update');
        Route::get('/surat-penghasilan/{permohonan}/pdf', [SuratPenghasilanController::class, 'generatePDF'])->name('admin.surat-penghasilan.pdf');
        
        // Audit Trail Management for Admin
        Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('admin.audit-trail.index');
        Route::get('/audit-trail/{auditTrail}', [AuditTrailController::class, 'show'])->name('admin.audit-trail.show');
        
        // Report Management for Admin
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export.excel');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export.pdf');
    });

// Operator Routes
Route::prefix('operator')
    ->middleware(['auth', 'role:operator'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'operatorDashboard'])->name('operator.dashboard');
        
        // Permohonan Management for Operator
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('operator.permohonan.index');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('operator.permohonan.show');
        Route::get('/permohonan/{permohonan}/edit', [PermohonanController::class, 'edit'])->name('operator.permohonan.edit');
        Route::put('/permohonan/{permohonan}', [PermohonanController::class, 'update'])->name('operator.permohonan.update');
        Route::patch('/permohonan/{permohonan}/status', [PermohonanController::class, 'updateStatus'])->name('operator.permohonan.updateStatus');
        Route::post('/permohonan/{permohonan}/signature', [PermohonanController::class, 'addSignature'])->name('operator.permohonan.addSignature');
        Route::get('/permohonan/{permohonan}/download', [PermohonanController::class, 'download'])->name('operator.permohonan.download');
        Route::get('/permohonan/{permohonan}/dokumen/{documentId}/download', [PermohonanController::class, 'downloadDocument'])->name('operator.permohonan.downloadDocument');
        Route::delete('/permohonan/{permohonan}', [PermohonanController::class, 'destroy'])->name('operator.permohonan.destroy');
        
        // File Upload Management for Operator
        Route::post('/permohonan/{permohonan}/upload', [FileUploadController::class, 'uploadDocument'])->name('operator.permohonan.upload');
        Route::delete('/dokumen/{dokumen}', [FileUploadController::class, 'deleteDocument'])->name('operator.dokumen.delete');
        Route::get('/dokumen/{dokumen}/download', [FileUploadController::class, 'downloadDocument'])->name('operator.dokumen.download');
        Route::post('/permohonan/{permohonan}/upload-letter', [FileUploadController::class, 'uploadGeneratedLetter'])->name('operator.permohonan.upload-letter');
        
        // Letter Type Management for Operator
        Route::get('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'show'])->name('operator.surat-domisili-tinggal.show');
        Route::get('/surat-domisili-tinggal/{permohonan}/edit', [SuratDomisiliTinggalController::class, 'edit'])->name('operator.surat-domisili-tinggal.edit');
        Route::put('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'update'])->name('operator.surat-domisili-tinggal.update');
        Route::get('/surat-domisili-tinggal/{permohonan}/pdf', [SuratDomisiliTinggalController::class, 'generatePDF'])->name('operator.surat-domisili-tinggal.pdf');
        
        Route::get('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'show'])->name('operator.surat-domisili-usaha.show');
        Route::get('/surat-domisili-usaha/{permohonan}/edit', [SuratDomisiliUsahaController::class, 'edit'])->name('operator.surat-domisili-usaha.edit');
        Route::put('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'update'])->name('operator.surat-domisili-usaha.update');
        Route::get('/surat-domisili-usaha/{permohonan}/pdf', [SuratDomisiliUsahaController::class, 'generatePDF'])->name('operator.surat-domisili-usaha.pdf');
        
        Route::get('/surat-kematian/{permohonan}', [SuratKematianController::class, 'show'])->name('operator.surat-kematian.show');
        Route::get('/surat-kematian/{permohonan}/edit', [SuratKematianController::class, 'edit'])->name('operator.surat-kematian.edit');
        Route::put('/surat-kematian/{permohonan}', [SuratKematianController::class, 'update'])->name('operator.surat-kematian.update');
        Route::get('/surat-kematian/{permohonan}/pdf', [SuratKematianController::class, 'generatePDF'])->name('operator.surat-kematian.pdf');
        
        Route::get('/surat-nikah/{permohonan}', [SuratNikahController::class, 'show'])->name('operator.surat-nikah.show');
        Route::get('/surat-nikah/{permohonan}/edit', [SuratNikahController::class, 'edit'])->name('operator.surat-nikah.edit');
        Route::put('/surat-nikah/{permohonan}', [SuratNikahController::class, 'update'])->name('operator.surat-nikah.update');
        Route::get('/surat-nikah/{permohonan}/pdf', [SuratNikahController::class, 'generatePDF'])->name('operator.surat-nikah.pdf');
        
        Route::get('/surat-mandah/{permohonan}', [SuratMandahController::class, 'show'])->name('operator.surat-mandah.show');
        Route::get('/surat-mandah/{permohonan}/edit', [SuratMandahController::class, 'edit'])->name('operator.surat-mandah.edit');
        Route::put('/surat-mandah/{permohonan}', [SuratMandahController::class, 'update'])->name('operator.surat-mandah.update');
        Route::get('/surat-mandah/{permohonan}/pdf', [SuratMandahController::class, 'generatePDF'])->name('operator.surat-mandah.pdf');
        
        Route::get('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'show'])->name('operator.surat-penghasilan.show');
        Route::get('/surat-penghasilan/{permohonan}/edit', [SuratPenghasilanController::class, 'edit'])->name('operator.surat-penghasilan.edit');
        Route::put('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'update'])->name('operator.surat-penghasilan.update');
        Route::get('/surat-penghasilan/{permohonan}/pdf', [SuratPenghasilanController::class, 'generatePDF'])->name('operator.surat-penghasilan.pdf');
        
        // Report Management for Operator
        Route::get('/reports', [ReportController::class, 'index'])->name('operator.reports.index');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('operator.reports.export.excel');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('operator.reports.export.pdf');
    });

// Warga Routes
Route::prefix('warga')
    ->middleware(['auth', 'role:warga'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'wargaDashboard'])->name('warga.dashboard');
        
        // Permohonan Management for Warga
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('warga.permohonan.index');
        Route::get('/permohonan/create', [PermohonanController::class, 'create'])->name('warga.permohonan.create');
        Route::post('/permohonan', [PermohonanController::class, 'store'])->name('warga.permohonan.store');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('warga.permohonan.show');
        Route::get('/permohonan/{permohonan}/download', [PermohonanController::class, 'download'])->name('warga.permohonan.download');
        Route::get('/permohonan/{permohonan}/dokumen/{documentId}/download', [PermohonanController::class, 'downloadDocument'])->name('warga.permohonan.downloadDocument');
        
        // File Upload Management for Warga
        Route::post('/permohonan/{permohonan}/upload', [FileUploadController::class, 'uploadDocument'])->name('warga.permohonan.upload');
        Route::get('/dokumen/{dokumen}/download', [FileUploadController::class, 'downloadDocument'])->name('warga.dokumen.download');
        
        // Letter Type Creation for Warga
        Route::get('/surat-domisili-tinggal/{permohonan}/create', [SuratDomisiliTinggalController::class, 'create'])->name('warga.surat-domisili-tinggal.create');
        Route::post('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'store'])->name('warga.surat-domisili-tinggal.store');
        Route::get('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'show'])->name('warga.surat-domisili-tinggal.show');
        Route::get('/surat-domisili-tinggal/{permohonan}/edit', [SuratDomisiliTinggalController::class, 'edit'])->name('warga.surat-domisili-tinggal.edit');
        Route::put('/surat-domisili-tinggal/{permohonan}', [SuratDomisiliTinggalController::class, 'update'])->name('warga.surat-domisili-tinggal.update');
        
        Route::get('/surat-domisili-usaha/{permohonan}/create', [SuratDomisiliUsahaController::class, 'create'])->name('warga.surat-domisili-usaha.create');
        Route::post('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'store'])->name('warga.surat-domisili-usaha.store');
        Route::get('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'show'])->name('warga.surat-domisili-usaha.show');
        Route::get('/surat-domisili-usaha/{permohonan}/edit', [SuratDomisiliUsahaController::class, 'edit'])->name('warga.surat-domisili-usaha.edit');
        Route::put('/surat-domisili-usaha/{permohonan}', [SuratDomisiliUsahaController::class, 'update'])->name('warga.surat-domisili-usaha.update');
        
        Route::get('/surat-kematian/{permohonan}/create', [SuratKematianController::class, 'create'])->name('warga.surat-kematian.create');
        Route::post('/surat-kematian/{permohonan}', [SuratKematianController::class, 'store'])->name('warga.surat-kematian.store');
        Route::get('/surat-kematian/{permohonan}', [SuratKematianController::class, 'show'])->name('warga.surat-kematian.show');
        Route::get('/surat-kematian/{permohonan}/edit', [SuratKematianController::class, 'edit'])->name('warga.surat-kematian.edit');
        Route::put('/surat-kematian/{permohonan}', [SuratKematianController::class, 'update'])->name('warga.surat-kematian.update');
        
        Route::get('/surat-nikah/{permohonan}/create', [SuratNikahController::class, 'create'])->name('warga.surat-nikah.create');
        Route::post('/surat-nikah/{permohonan}', [SuratNikahController::class, 'store'])->name('warga.surat-nikah.store');
        Route::get('/surat-nikah/{permohonan}', [SuratNikahController::class, 'show'])->name('warga.surat-nikah.show');
        Route::get('/surat-nikah/{permohonan}/edit', [SuratNikahController::class, 'edit'])->name('warga.surat-nikah.edit');
        Route::put('/surat-nikah/{permohonan}', [SuratNikahController::class, 'update'])->name('warga.surat-nikah.update');
        
        Route::get('/surat-mandah/{permohonan}/create', [SuratMandahController::class, 'create'])->name('warga.surat-mandah.create');
        Route::post('/surat-mandah/{permohonan}', [SuratMandahController::class, 'store'])->name('warga.surat-mandah.store');
        Route::get('/surat-mandah/{permohonan}', [SuratMandahController::class, 'show'])->name('warga.surat-mandah.show');
        Route::get('/surat-mandah/{permohonan}/edit', [SuratMandahController::class, 'edit'])->name('warga.surat-mandah.edit');
        Route::put('/surat-mandah/{permohonan}', [SuratMandahController::class, 'update'])->name('warga.surat-mandah.update');
        
        Route::get('/surat-penghasilan/{permohonan}/create', [SuratPenghasilanController::class, 'create'])->name('warga.surat-penghasilan.create');
        Route::post('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'store'])->name('warga.surat-penghasilan.store');
        Route::get('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'show'])->name('warga.surat-penghasilan.show');
        Route::get('/surat-penghasilan/{permohonan}/edit', [SuratPenghasilanController::class, 'edit'])->name('warga.surat-penghasilan.edit');
        Route::put('/surat-penghasilan/{permohonan}', [SuratPenghasilanController::class, 'update'])->name('warga.surat-penghasilan.update');
    });