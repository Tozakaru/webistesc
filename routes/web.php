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

Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('role:Admin,User');

Route::middleware('role:Admin')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    Route::patch('/mahasiswa/{id}/toggle-uid', [MahasiswaController::class, 'toggleUidStatus'])
        ->name('mahasiswa.toggleUidStatus');
});

Route::middleware('role:Admin')->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::get('/dosen/{id}', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');

    Route::patch('/dosen/{id}/toggle-uid', [DosenController::class, 'toggleUidStatus'])
        ->name('dosen.toggleUidStatus');
});

Route::post('/scan-rfid/ruangan1', [LogAktivitasController::class, 'scanRuangan1']);
Route::post('/scan-rfid/ruangan2', [LogAktivitasController::class, 'scanRuangan2']);

Route::middleware('role:Admin,User')->group(function () {
    Route::get('/log/ruangan1', [LogAktivitasController::class, 'ruangan1'])->name('log.ruangan1');
    Route::get('/log/ruangan2', [LogAktivitasController::class, 'ruangan2'])->name('log.ruangan2');

    Route::get('/log/{ruangan}', function (string $ruangan) {
        if ($ruangan === 'ruangan1') {
            return redirect()->route('log.ruangan1');
        }
        if ($ruangan === 'ruangan2') {
            return redirect()->route('log.ruangan2');
        }
        abort(404);
    })->name('log.ruangan');
});

Route::middleware('role:Admin,User')->group(function () {
    Route::get('/rekapanaktivitas', [LogAktivitasController::class, 'rekapan'])->name('rekapan.index');
});
Route::get('/rekapan/export/csv', [LogAktivitasController::class, 'exportCsv'])->name('rekapan.export.csv');
Route::get('/rekapan/export/pdf', [LogAktivitasController::class, 'exportPdf'])->name('rekapan.export.pdf');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/account-list', [UserController::class, 'index'])->name('users.index');
    Route::get('/account-list/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/account-list', [UserController::class, 'store'])->name('users.store');
    Route::get('/account-list/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/account-list/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/account-list/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/account-list/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::delete('/account-list/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/force-open', [ForceOpenController::class, 'execute'])
        ->middleware('throttle:5,1')
        ->name('force-open.execute');
});

Route::get('/profile', [UserController::class, 'profile_view'])->middleware('role:Admin,User');
Route::post('/profile/{id}', [UserController::class, 'update_profile'])->middleware('role:Admin,User');
Route::get('/change-password', [UserController::class, 'change_password_view'])->middleware('role:Admin,User');
Route::post('/change-password/{id}', [UserController::class, 'change_password'])->middleware('role:Admin,User');

Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

Route::post('/esp/heartbeat', [EspDeviceController::class, 'heartbeat'])->middleware('throttle:180,1');
Route::post('/esp/ack',       [EspDeviceController::class, 'ack'])->middleware('throttle:180,1');

Route::get('/aktivitas-invalid', [LogAktivitasController::class, 'aktivitasInvalid'])
    ->name('aktivitas.invalid');
