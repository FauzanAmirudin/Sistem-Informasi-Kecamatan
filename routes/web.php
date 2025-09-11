<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DesaController as AdminDesaController;
use App\Http\Controllers\Admin\PendudukController as AdminPendudukController;
use App\Http\Controllers\Admin\PerangkatDesaController as AdminPerangkatDesaController;
use App\Http\Controllers\Admin\AsetDesaController as AdminAsetDesaController;
use App\Http\Controllers\Admin\AsetTanahWargaController as AdminAsetTanahWargaController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminDesa\DashboardController as AdminDesaDashboardController;
use App\Http\Controllers\AdminDesa\ProfilDesaController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Kecamatan Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring', [AdminDashboardController::class, 'monitoring'])->name('monitoring');
    Route::get('/statistik', [AdminDashboardController::class, 'statistik'])->name('statistik');
    Route::get('/statistik/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('statistik.export.excel');
    Route::get('/statistik/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('statistik.export.pdf');
    // Tambahkan di dalam group admin routes
    Route::get('penduduk/download-template', [AdminPendudukController::class, 'downloadTemplate'])->name('penduduk.download-template');
    
    // Data Desa
    Route::resource('desa', AdminDesaController::class);
    Route::post('desa/{desa}/update-coordinates', [AdminDesaController::class, 'updateCoordinates'])->name('desa.update-coordinates');
    
    // Perangkat Desa
    Route::get('perangkat-desa/export/excel', [AdminPerangkatDesaController::class, 'exportExcel'])->name('perangkat-desa.export.excel');
    Route::resource('perangkat-desa', AdminPerangkatDesaController::class);
    Route::get('perangkat-desa/{perangkat}/riwayat', [AdminPerangkatDesaController::class, 'riwayat'])->name('perangkat-desa.riwayat');
    
    // Data Penduduk
    Route::resource('penduduk', AdminPendudukController::class);
    Route::get('penduduk/export/excel', [AdminPendudukController::class, 'exportExcel'])->name('penduduk.export.excel');
    Route::get('penduduk/export/pdf', [AdminPendudukController::class, 'exportPdf'])->name('penduduk.export.pdf');
    Route::post('penduduk/import', [AdminPendudukController::class, 'import'])->name('penduduk.import');
    
    // Aset Desa
    Route::get('aset-desa/export/pdf', [AdminAsetDesaController::class, 'exportPdf'])->name('aset-desa.export.pdf');
    Route::resource('aset-desa', AdminAsetDesaController::class);
    Route::get('aset-desa/{aset}/riwayat', [AdminAsetDesaController::class, 'riwayat'])->name('aset-desa.riwayat');
    
    // Aset Tanah Warga
    Route::get('aset-tanah-warga/export/excel', [AdminAsetTanahWargaController::class, 'exportExcel'])->name('aset-tanah-warga.export.excel');
    Route::resource('aset-tanah-warga', AdminAsetTanahWargaController::class);
    
    // Dokumen
    Route::resource('dokumen', AdminDokumenController::class)->parameters([
        'dokumen' => 'dokuman'
    ]);
    Route::get('dokumen/{dokuman}/download', [AdminDokumenController::class, 'download'])->name('dokumen.download');
    
    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
});

// Admin Desa Routes
Route::prefix('admin-desa')->name('admin-desa.')->middleware(['auth', 'admin_desa'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDesaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/excel', [AdminDesaDashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [AdminDesaDashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');
    
    // Data Desa (Terbatas)
    Route::get('/desa', '\App\Http\Controllers\AdminDesa\DesaController@index')->name('desa.index');
    Route::get('/desa/edit', '\App\Http\Controllers\AdminDesa\DesaController@edit')->name('desa.edit');
    Route::put('/desa', '\App\Http\Controllers\AdminDesa\DesaController@update')->name('desa.update');
    Route::get('/desa/show', '\App\Http\Controllers\AdminDesa\DesaController@show')->name('desa.show');
    
    // Profil Desa
    Route::get('/profil', '\App\Http\Controllers\AdminDesa\ProfilDesaController@index')->name('profil.index');
    Route::get('/profil/edit', '\App\Http\Controllers\AdminDesa\ProfilDesaController@edit')->name('profil.edit');
    Route::put('/profil', '\App\Http\Controllers\AdminDesa\ProfilDesaController@update')->name('profil.update');
    Route::get('/profil/download-monografi', '\App\Http\Controllers\AdminDesa\ProfilDesaController@downloadMonografi')->name('profil.download-monografi');
    
    // Data Penduduk
    Route::resource('penduduk', '\App\Http\Controllers\AdminDesa\PendudukController');
    Route::get('penduduk/export/excel', '\App\Http\Controllers\AdminDesa\PendudukController@exportExcel')->name('penduduk.export.excel');
    Route::get('penduduk/export-excel', '\App\Http\Controllers\AdminDesa\PendudukController@exportExcel')->name('penduduk.export-excel');
    Route::get('penduduk/export/pdf', '\App\Http\Controllers\AdminDesa\PendudukController@exportPdf')->name('penduduk.export.pdf');
    Route::get('penduduk/template', '\App\Http\Controllers\AdminDesa\PendudukController@downloadTemplate')->name('penduduk.template');
    Route::post('penduduk/import', '\App\Http\Controllers\AdminDesa\PendudukController@import')->name('penduduk.import');
    
    // Perangkat Desa
    Route::resource('perangkat-desa', '\App\Http\Controllers\AdminDesa\PerangkatDesaController');
    Route::get('perangkat-desa/export/excel', '\App\Http\Controllers\AdminDesa\PerangkatDesaController@exportExcel')->name('perangkat-desa.export.excel');
    Route::get('perangkat-desa/{perangkat}/riwayat', '\App\Http\Controllers\AdminDesa\PerangkatDesaController@riwayat')->name('perangkat-desa.riwayat');
    
    // Aset Desa
    Route::resource('aset-desa', '\App\Http\Controllers\AdminDesa\AsetDesaController');
    Route::get('aset-desa/export/pdf', '\App\Http\Controllers\AdminDesa\AsetDesaController@exportPdf')->name('aset-desa.export.pdf');
    Route::get('aset-desa/{aset}/riwayat', '\App\Http\Controllers\AdminDesa\AsetDesaController@riwayat')->name('aset-desa.riwayat');
    
    // Aset Tanah Warga
    Route::get('aset-tanah-warga/rekap', '\App\Http\Controllers\AdminDesa\AsetTanahWargaController@rekap')->name('aset-tanah-warga.rekap');
    Route::get('aset-tanah-warga/export/excel', '\App\Http\Controllers\AdminDesa\AsetTanahWargaController@exportExcel')->name('aset-tanah-warga.exportExcel');
    Route::get('aset-tanah-warga/export/rekap-excel', '\App\Http\Controllers\AdminDesa\AsetTanahWargaController@exportRekapExcel')->name('aset-tanah-warga.exportRekapExcel');
    Route::get('aset-tanah-warga/export/rekap-pdf', '\App\Http\Controllers\AdminDesa\AsetTanahWargaController@exportRekapPdf')->name('aset-tanah-warga.exportRekapPdf');
    Route::resource('aset-tanah-warga', '\App\Http\Controllers\AdminDesa\AsetTanahWargaController');
    
    // Dokumen
    Route::resource('dokumen', '\App\Http\Controllers\AdminDesa\DokumenController')->parameters([
        'dokumen' => 'dokuman'
    ]);
    Route::get('dokumen/{dokuman}/download', '\App\Http\Controllers\AdminDesa\DokumenController@download')->name('dokumen.download');
}); 

// Redirect after login
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    if ($user->role === 'admin_kecamatan') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'admin_desa') {
        return redirect()->route('admin-desa.dashboard');
    }
    
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');