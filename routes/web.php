<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspDeviceController;

// Auth
Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', function () {
    return view('pages/dashboard');
})->middleware('role:Admin,User');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->middleware('role:Admin');
Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->middleware('role:Admin');
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'edit'])->middleware('role:Admin');
Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->middleware('role:Admin');
Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->middleware('role:Admin');
Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->middleware('role:Admin');

// Endpoint scan RFID (tetap)
Route::post('/scan-rfid/ruangan1', [LogAktivitasController::class, 'scanRuangan1']);
Route::post('/scan-rfid/ruangan2', [LogAktivitasController::class, 'scanRuangan2']);

// --- Livewire: halaman log per ruangan (1 view untuk semua ruangan) ---
Route::get('/log/{ruangan}', function (string $ruangan) {
    // Batasi hanya ruangan yang didukung
    if (!in_array($ruangan, ['ruangan1', 'ruangan2'])) {
        abort(404);
    }
    return view('pages.logaktivitas.ruangan', compact('ruangan'));
})->middleware('role:Admin,User')->name('log.ruangan');

// --- Kompatibilitas rute lama (redirect ke rute baru) ---
Route::get('/log/ruangan1', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan1']))->name('log.ruangan1');
Route::get('/log/ruangan2', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan2']))->name('log.ruangan2');
Route::get('/logaktivitas/ruangan1', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan1']))->middleware('role:Admin,User');
Route::get('/logaktivitas/ruangan2', fn() => redirect()->route('log.ruangan', ['ruangan' => 'ruangan2']))->middleware('role:Admin,User');

// Rekapan aktivitas
Route::get('/rekapanaktivitas', [LogAktivitasController::class, 'rekapan'])->name('rekapan.index')->middleware('role:Admin,User');
Route::get('/rekapan/export/csv', [LogAktivitasController::class, 'exportCsv'])->name('rekapan.export.csv');
Route::get('/rekapan/export/pdf', [LogAktivitasController::class, 'exportPdf'])->name('rekapan.export.pdf');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Index daftar akun (tetap di /account-list biar kompatibel dengan link lama)
    Route::get('/account-list', [UserController::class, 'index'])->name('users.index');
    // Buat akun oleh admin
    Route::get('/account-list/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/account-list', [UserController::class, 'store'])->name('users.store');
    // Edit/Update akun
    Route::get('/account-list/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/account-list/{user}', [UserController::class, 'update'])->name('users.update');
    // Aktivasi/Nonaktivasi akun
    Route::patch('/account-list/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('/account-list/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    // Hapus akun
    Route::delete('/account-list/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    // (Opsional) alias ke /users kalau mau gaya REST
    Route::get('/users', [UserController::class, 'index'])->name('users.index.alias');
});

Route::get('/profile', [UserController::class, 'profile_view'])->middleware('role:Admin,User');
Route::post('/profile/{id}', [UserController::class, 'update_profile'])->middleware('role:Admin,User');
Route::get('/change-password', [UserController::class, 'change_password_view'])->middleware('role:Admin,User');
Route::post('/change-password/{id}', [UserController::class, 'change_password'])->middleware('role:Admin,User');

Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
Route::post('/esp/heartbeat', [EspDeviceController::class, 'updateStatus'])->middleware('throttle:120,1');

Route::get('/aktivitas-invalid', [LogAktivitasController::class, 'aktivitasInvalid'])->name('aktivitas.invalid');

Route::patch('/mahasiswa/{id}/toggle-uid', [MahasiswaController::class, 'toggleUidStatus'])->name('mahasiswa.toggleUidStatus');
