<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EspDeviceController;
use App\Http\Controllers\ForceOpenController;
use App\Http\Controllers\DosenController;

// ---------------------- Auth ----------------------
Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

// ---------------------- Dashboard ----------------------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('role:Admin,User'); // jangan ada route /dashboard lain (closure)

// ---------------------- Mahasiswa (Admin) ----------------------
Route::middleware('role:Admin')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // toggle UID yang sudah ada
    Route::patch('/mahasiswa/{id}/toggle-uid', [MahasiswaController::class, 'toggleUidStatus'])
        ->name('mahasiswa.toggleUidStatus');
});

// ---------------------- Dosen (Admin) ----------------------
Route::middleware('role:Admin')->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::get('/dosen/{id}', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');

    // opsional: nonaktifkan/aktifkan UID dosen
    Route::patch('/dosen/{id}/toggle-uid', [DosenController::class, 'toggleUidStatus'])
        ->name('dosen.toggleUidStatus');
});


// ---------------------- Scan RFID (tetap) ----------------------
Route::post('/scan-rfid/ruangan1', [LogAktivitasController::class, 'scanRuangan1']);
Route::post('/scan-rfid/ruangan2', [LogAktivitasController::class, 'scanRuangan2']);

// ---------------------- Log Aktivitas per ruangan ----------------------
Route::get('/log/{ruangan}', function (string $ruangan) {
    if (!in_array($ruangan, ['ruangan1', 'ruangan2'])) abort(404);
    return view('pages.logaktivitas.ruangan', compact('ruangan'));
})->middleware('role:Admin,User')->name('log.ruangan');

// Kompatibilitas rute lama
Route::get('/log/ruangan1', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan1']))->name('log.ruangan1');
Route::get('/log/ruangan2', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan2']))->name('log.ruangan2');
Route::get('/logaktivitas/ruangan1', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan1']))->middleware('role:Admin,User');
Route::get('/logaktivitas/ruangan2', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan2']))->middleware('role:Admin,User');

// ---------------------- Rekapan ----------------------
Route::get('/rekapanaktivitas', [LogAktivitasController::class, 'rekapan'])
    ->name('rekapan.index')->middleware('role:Admin,User');
Route::get('/rekapan/export/csv', [LogAktivitasController::class, 'exportCsv'])->name('rekapan.export.csv');
Route::get('/rekapan/export/pdf', [LogAktivitasController::class, 'exportPdf'])->name('rekapan.export.pdf');

// ---------------------- Users (Admin) ----------------------
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/account-list', [UserController::class, 'index'])->name('users.index');
    Route::get('/account-list/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/account-list', [UserController::class, 'store'])->name('users.store');
    Route::get('/account-list/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/account-list/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/account-list/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/account-list/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::delete('/account-list/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Force Open (Admin saja) — submit dari modal
    Route::post('/force-open', [ForceOpenController::class, 'execute'])
        ->middleware('throttle:5,1') // batasi 5x/menit
        ->name('force-open.execute');
});

// ---------------------- Profile ----------------------
Route::get('/profile', [UserController::class, 'profile_view'])->middleware('role:Admin,User');
Route::post('/profile/{id}', [UserController::class, 'update_profile'])->middleware('role:Admin,User');
Route::get('/change-password', [UserController::class, 'change_password_view'])->middleware('role:Admin,User');
Route::post('/change-password/{id}', [UserController::class, 'change_password'])->middleware('role:Admin,User');

// ---------------------- Chart data ----------------------
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

// ---------------------- ESP32 Endpoints ----------------------
Route::post('/esp/heartbeat', [EspDeviceController::class, 'heartbeat'])->middleware('throttle:180,1');
Route::post('/esp/ack',       [EspDeviceController::class, 'ack'])->middleware('throttle:180,1');

// ---------------------- Lainnya ----------------------
Route::get('/aktivitas-invalid', [LogAktivitasController::class, 'aktivitasInvalid'])->name('aktivitas.invalid');
Route::patch('/mahasiswa/{id}/toggle-uid', [MahasiswaController::class, 'toggleUidStatus'])->name('mahasiswa.toggleUidStatus');
