<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Mahasiswa\MahasiswaController; // Pastikan namespace ini benar
use App\Http\Controllers\Dosen\DosenController; // Pastikan namespace ini benar

// --- AKSES PUBLIK ---
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

    // --- AREA PROFIL & SETTINGS (Umum) ---
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile.show');
   
    // Rute untuk buka halaman edit (GET)
Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');

// Rute untuk simpan data (POST)
Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    
    
    // Settings (Menyatukan alias agar tidak error)
    Route::get('/settings', [AdminController::class, 'settingsShow'])->name('settings');
    Route::get('/settings/show', [AdminController::class, 'settingsShow'])->name('settings.show');


    // --- AREA ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store'); 
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
        // Tambahkan ekspor untuk admin jika diperlukan di masa depan
        Route::get('/rekap/export', [AdminController::class, 'exportExcelAdmin'])->name('rekap.export');
    });

// --- AREA DOSEN ---
Route::prefix('dosen')->name('dosen.')->group(function () {
    
    // Dashboard & Rekap
    Route::get('/dashboard', [DosenController::class, 'index'])->name('dashboard');
    Route::get('/rekap', [DosenController::class, 'rekapDosen'])->name('rekap');
    Route::get('/rekap/export/{id}', [DosenController::class, 'exportExcel'])->name('rekap.export');
    Route::get('/dosen/rekap/export-semester/{id}', [DosenController::class, 'exportSemester'])->name('dosen.rekap.export_semester');
    
    // Pengelolaan Kelas
    Route::get('/kelas/tambah', [DosenController::class, 'createKelas'])->name('kelas.tambah');
    Route::post('/kelas/store', [DosenController::class, 'storeKelas'])->name('kelas.store');
    Route::get('/kelas/edit/{id}', [DosenController::class, 'editKelas'])->name('kelas.edit');
    Route::put('/kelas/update/{id}', [DosenController::class, 'update'])->name('kelas.update');
    Route::delete('/hapus-kelas/{id}', [DosenController::class, 'destroyKelas'])->name('hapus_kelas');

    // --- BAGIAN SESI ABSENSI (PENYEBAB TOMBOL QR TIDAK JALAN) ---
    Route::get('/buka-absen/{id}', [DosenController::class, 'bukaAbsen'])->name('buka_absen');
    Route::post('/sesi/store', [DosenController::class, 'storeSesi'])->name('sesi.store'); // <-- PENTING: Harus POST
    Route::get('/show-qr/{id}', [DosenController::class, 'showQR'])->name('show_qr'); // <-- PENTING: Untuk nampilin QR
    
});

    // --- AREA MAHASISWA ---
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/rekap', [AdminController::class, 'rekapAbsensi'])->name('rekap');
        Route::get('/mahasiswa/rekap/export/{id}', [MahasiswaController::class, 'exportSemesterMahasiswa'])->name('mahasiswa.rekap.export');
        
        // Fitur Download Excel Mahasiswa
        Route::get('/rekap/export', [AdminController::class, 'exportExcelMahasiswa'])->name('rekap.export');
        
        Route::post('/scan', [AdminController::class, 'scanAbsensi'])->name('absensi.scan');
    });

    // Global Alias (Optional)
    Route::post('/absensi/scan-global', [AdminController::class, 'scanAbsensi'])->name('absensi.scan.global');
});