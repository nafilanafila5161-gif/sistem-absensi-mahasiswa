<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Dosen\DosenController;

// --- AKSES PUBLIK (Login & Forgot Password) ---
Route::get('/', function () { 
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AdminController::class, 'login'])->name('login.post');

// Rute Lupa Password
Route::get('/forgot-password', [AdminController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password-update', [AdminController::class, 'updatePassword'])->name('password.update');


// --- AKSES TERPROTEKSI (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // --- AREA PROFIL & SETTINGS (Rute Umum) ---
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile.show');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    
    // Menyatukan rute settings agar tidak error di image_639dc6 & image_638e84
    Route::get('/settings', [AdminController::class, 'settingsShow'])->name('settings.show');
    // Alias 'settings' untuk mendukung {{ route('settings') }}
    Route::get('/settings/alias', [AdminController::class, 'settingsShow'])->name('settings');


    // --- AREA ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store'); 
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    });


    // --- AREA DOSEN ---
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/kelas/create', [AdminController::class, 'createKelas'])->name('kelas.create');
        Route::post('/kelas/store', [DosenController::class, 'storeKelas'])->name('kelas.store');
        Route::get('/rekap', [AdminController::class, 'rekapDosen'])->name('rekap');
     // Pastikan di dalam group 'dosen'
Route::get('/absensi/pantau', [AdminController::class, 'pantauAbsensi'])->name('absensi.pantau');
    
    Route::get('/kelas/create', [AdminController::class, 'createKelas'])->name('kelas.create');
    // Rute untuk FORM TAMBAH KELAS (Ini yang harusnya dipanggil tombol biru)
    Route::get('/kelas/tambah', [AdminController::class, 'createKelasForm'])->name('kelas.tambah');
    
    // Rute untuk PROSES SIMPAN KELAS
    Route::post('/kelas/store', [AdminController::class, 'storeKelas'])->name('kelas.store');

    // Rute untuk BUKA ABSENSI (Halaman yang muncul di image_62a23f)
    Route::get('/absensi/buka', [AdminController::class, 'bukaAbsenPage'])->name('absensi.buka');
    Route::post('/dosen/absensi/store', [AdminController::class, 'storeSesi'])->name('dosen.absensi.store');
    // Rute untuk menampilkan form (GET)
Route::get('/dosen/kelas/buka/{id}', [AdminController::class, 'bukaAbsen'])->name('dosen.kelas.buka');

// Rute untuk memproses simpan sesi (POST)
Route::post('/dosen/sesi/store', [AdminController::class, 'storeSesi'])->name('dosen.sesi.store');
    });


    // --- AREA MAHASISWA ---
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/scan', [AdminController::class, 'scanAbsensi'])->name('absensi.scan');
        Route::get('/rekap', [AdminController::class, 'rekapAbsensi'])->name('rekap');
    });

    // Alias global untuk scan absensi agar tombol di blade tidak error
    Route::post('/absensi/scan', [AdminController::class, 'scanAbsensi'])->name('absensi.scan');
    Route::get('/settings', [AdminController::class, 'settingsShow'])->name('settings');
    // Tambahkan di dalam group dosen atau admin
Route::get('/settings', [AdminController::class, 'settingsShow'])->name('settings.show');

});