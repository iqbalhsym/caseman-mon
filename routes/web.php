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
use App\Http\Controllers\PencarianController;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

// Route::get('/', [PencarianController::class, 'index'])->name('home');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login');
Route::get('/refresh-captcha', [LoginController::class, 'refreshCaptcha']);
Route::post('logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return to_route('login');
})->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::post('obat/import', [ObatController::class, 'import'])->name('obat.import');
    Route::get('obat/export', [ObatController::class, 'export'])->name('obat.export');
    Route::get('obat-search', [ObatController::class, 'searchObat'])->name('obat.search');

    Route::resources([
        'lokasi' => LokasiController::class,
        'shift' => ShiftController::class,
        'permintaan' => PermintaanController::class,
        'ganti-password' => GantiPasswordController::class,
        'laporan' => LaporanController::class,
        'list-permintaan' => ListPermintaanController::class,
        'viewer' => ViewerController::class,
        'penjamin' => PenjaminController::class,
        'user' => UserController::class,
        'obat' => ObatController::class,
    ]);

    Route::middleware('role:administrator')->group(function () {
        Route::resources([
            'role' => RoleController::class,
        ]);
    });

    // Search permintaan by name or no_rm (AJAX) - for list/viewer
    Route::get('permintaan-search', [PermintaanController::class, 'search'])->name('permintaan.search');
    Route::get('permintaan-patient-name', [PermintaanController::class, 'getPatientName'])->name('permintaan.patientName');

    // Search patient by no_rm for create form autocomplete
    Route::get('permintaan-search-rm', [PermintaanController::class, 'searchRm'])->name('permintaan.search.rm');

    // Get patient history by RM and category
    Route::get('permintaan-riwayat-rm', [PermintaanController::class, 'riwayat'])->name('permintaan.riwayat.rm');

    Route::post('update-permintaan', [PermintaanController::class, 'ubah'])->name('update-permintaan');
});

Route::get('hapus-data', function () {
    $data = Permintaan::where('created_at', '<=', '2025-09-24')->get();

    foreach ($data as $item){
        $item->delete();
    }
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
