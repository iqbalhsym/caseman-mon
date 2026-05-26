<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Laporan\LaporanController;
use App\Http\Controllers\Admin\Lokasi\LokasiController;
use App\Http\Controllers\Admin\Obat\ObatController;
use App\Http\Controllers\Admin\Password\GantiPasswordController;
use App\Http\Controllers\Admin\Penjamin\PenjaminController;
use App\Http\Controllers\Admin\Permintaan\ListPermintaanController;
use App\Http\Controllers\Admin\Permintaan\PermintaanController;
use App\Http\Controllers\Admin\Shift\ShiftController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Viewer\ViewerController;
use App\Http\Controllers\LoginController;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login');
Route::get('/refresh-captcha', [LoginController::class, 'refreshCaptcha']);
Route::post('logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return to_route('login');
})->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // =====================
    // SEMUA ROLE (sudah login)
    // =====================
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('ganti-password', [GantiPasswordController::class, 'index'])->name('ganti-password.index');
    Route::post('ganti-password', [GantiPasswordController::class, 'store'])->name('ganti-password.store');

    // =====================
    // ADMINISTRATOR
    // =====================
    Route::middleware('role:administrator')->group(function () {

        Route::resources([
            'role'    => RoleController::class,
            'user'    => UserController::class,
            'lokasi'  => LokasiController::class,
            'penjamin' => PenjaminController::class,
            'shift'   => ShiftController::class,
        ]);

        Route::post('obat/import', [ObatController::class, 'import'])->name('obat.import');
        Route::get('obat/export', [ObatController::class, 'export'])->name('obat.export');
        Route::get('obat-search', [ObatController::class, 'searchObat'])->name('obat.search');
        Route::resource('obat', ObatController::class);

        Route::resource('laporan', LaporanController::class);

        Route::resource('permintaan', PermintaanController::class);
        Route::post('update-permintaan', [PermintaanController::class, 'ubah'])->name('update-permintaan');
        Route::get('permintaan-search', [PermintaanController::class, 'search'])->name('permintaan.search');
        Route::get('permintaan-search-rm', [PermintaanController::class, 'searchRm'])->name('permintaan.search.rm');
        Route::get('permintaan-riwayat-rm', [PermintaanController::class, 'riwayat'])->name('permintaan.riwayat.rm');
        Route::get('permintaan-patient-name', [PermintaanController::class, 'getPatientName'])->name('permintaan.patientName');

        Route::resources([
            'list-permintaan' => ListPermintaanController::class,
            'viewer'          => ViewerController::class,
        ]);
    });

    // =====================
    // TENAGA MEDIS
    // =====================
    Route::middleware('role:tenagamedis')->group(function () {

        Route::get('permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
        Route::post('permintaan', [PermintaanController::class, 'store'])->name('permintaan.store');
        Route::get('permintaan/{permintaan}', [PermintaanController::class, 'show'])->name('permintaan.show');
        Route::get('permintaan/{permintaan}/edit', [PermintaanController::class, 'edit'])->name('permintaan.edit');
        Route::post('update-permintaan', [PermintaanController::class, 'ubah'])->name('update-permintaan');
        Route::delete('permintaan/{permintaan}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');

        Route::get('permintaan-search', [PermintaanController::class, 'search'])->name('permintaan.search');
        Route::get('permintaan-search-rm', [PermintaanController::class, 'searchRm'])->name('permintaan.search.rm');
        Route::get('permintaan-riwayat-rm', [PermintaanController::class, 'riwayat'])->name('permintaan.riwayat.rm');
        Route::get('permintaan-patient-name', [PermintaanController::class, 'getPatientName'])->name('permintaan.patientName');
    });

    // =====================
    // VIEWER
    // =====================
    Route::middleware('role:viewer')->group(function () {

        Route::get('viewer', [ViewerController::class, 'index'])->name('viewer.index');
        Route::get('permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('permintaan/{permintaan}', [PermintaanController::class, 'show'])->name('permintaan.show');
        Route::get('permintaan-search', [PermintaanController::class, 'search'])->name('permintaan.search');
        Route::get('list-permintaan', [ListPermintaanController::class, 'index'])->name('list-permintaan.index');
    });

    // =====================
    // CASE MANAGER
    // =====================
    Route::middleware('role:casemanager')->group(function () {

        Route::get('permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('permintaan/{permintaan}', [PermintaanController::class, 'show'])->name('permintaan.show');
        Route::get('permintaan/{permintaan}/edit', [PermintaanController::class, 'edit'])->name('permintaan.edit');
        Route::post('update-permintaan', [PermintaanController::class, 'ubah'])->name('update-permintaan');

        Route::resource('list-permintaan', ListPermintaanController::class);
        Route::resource('shift', ShiftController::class);

        Route::get('permintaan-search', [PermintaanController::class, 'search'])->name('permintaan.search');
        Route::get('permintaan-search-rm', [PermintaanController::class, 'searchRm'])->name('permintaan.search.rm');
        Route::get('permintaan-riwayat-rm', [PermintaanController::class, 'riwayat'])->name('permintaan.riwayat.rm');
        Route::get('permintaan-patient-name', [PermintaanController::class, 'getPatientName'])->name('permintaan.patientName');
        Route::get('obat-search', [ObatController::class, 'searchObat'])->name('obat.search');
    });

});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});