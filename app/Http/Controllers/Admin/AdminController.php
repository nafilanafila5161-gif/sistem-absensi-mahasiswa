<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Str;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaAbsensiExport;



class AdminController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            return redirect()->intended($role . '/dashboard'); 
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

  public function index()
{
    $user = Auth::user();

    // 1. Logika untuk ADMIN
    if ($user->role == 'admin') {
        $stats = [
            'total_mahasiswa' => \App\Models\User::where('role', 'mahasiswa')->count(),
            'total_dosen'     => \App\Models\User::where('role', 'dosen')->count(),
            'total_kelas'     => \App\Models\Kelas::count(),
        ];
        // Pastikan file ini ada di resources/views/Admin/dashboard.blade.php
        return view('Admin.dashboard', compact('stats'));
    }

    // 2. Logika untuk DOSEN
    if ($user->role == 'dosen') {
        $kelas = \App\Models\Kelas::where('dosen_id', $user->id)->get();
        // Pastikan file ini ada di resources/views/Dosen/dashboard.blade.php
        return view('Dosen.dashboard', compact('kelas'));
    }

    // 3. Logika untuk MAHASISWA
    if ($user->role == 'mahasiswa') {
        // Tambahkan logika data mahasiswa di sini jika perlu
        return view('Mahasiswa.dashboard');
    }

    return redirect('/login');
}

    public function dashboardDosen()
{
    $userId = Auth::id();
    $dosen = Dosen::where('user_id', $userId)->first();

    // SOLUSI: Jika dosen tidak ada, buat koleksi kosong agar count() tidak error
    if (!$dosen) {
        $daftar_kelas = collect(); 
    } else {
        $daftar_kelas = Kelas::where('dosen_id', $dosen->id)->get();
    }

    return view('Dosen.dashboard', compact('daftar_kelas'));
}

 public function storeKelas(Request $request)
{
    $request->validate([
        'nama_mk' => 'required',
        'kode_kelas' => 'required',
        'sks' => 'required|numeric',
        'hari' => 'required',
    ]);

    Kelas::create([
        'nama_mk' => $request->nama_mk,
        'kode_kelas' => $request->kode_kelas,
        'sks' => $request->sks,
        'hari' => $request->hari,
        'dosen_id' => Auth::id(),
    ]);

    return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil disimpan!');
}

    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil dihapus!');
    }

   // Method ini khusus untuk menampilkan modal/halaman Buka Sesi (Gambar terakhir Anda)
// Method ini khusus untuk menampilkan modal/halaman Buka Sesi (Gambar terakhir Anda)
// Di AdminController fungsi bukaAbsen
public function bukaAbsen($id) {
    $kelas = Kelas::findOrFail($id); // Mencari data kelas berdasarkan ID di URL
    return view('dosen.buka_absen', compact('kelas'));
}

// Method baru untuk melihat detail kelas (Daftar hadir, materi, dll)
public function showDetailKelas($id) {
    $kelas = Kelas::with('mahasiswa')->findOrFail($id);
    return view('Dosen.detail_kelas', compact('kelas'));
}

// Method baru untuk melihat detail kelas (Daftar hadir, materi, dll)

    // Fungsi pendukung lainnya
    public function manageUsers() { 
        $users = User::all(); 
        return view('Admin.manage_users', compact('users')); 
    }

    public function storeUser(Request $request) {
        $request->validate(['name' => 'required', 'email' => 'required|unique:users', 'role' => 'required']);
        $plainPassword = Str::random(8); 
        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'role' => $request->role, 
            'password' => Hash::make($plainPassword)
        ]);
        Mail::to($user->email)->send(new UserCreatedMail($user, $plainPassword));
        return back()->with('success', 'User berhasil didaftarkan!');
    }

    public function destroy($id) { 
        User::findOrFail($id)->delete(); 
        return back()->with('success', 'User dihapus.'); 
    }

    public function profile() { 
        $user = Auth::user(); 
        return view('auth.profile', compact('user')); 
    }

    public function settingsShow() { return view('auth.settings'); }
    
    public function showForgotPassword() { return view('auth.forgot-password'); }

    public function updateProfile(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
    ]);

    $user = \App\Models\User::find(Auth::id());

    // 2. Cek apakah ada perubahan data
    if ($user->name === $request->name && $user->email === $request->email) {
        return back()->with('info', 'Tidak ada perubahan data profil.');
    }

    // 3. Jika ada perubahan, baru simpan
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return back()->with('success', 'Profil berhasil diperbarui!');
}

public function editProfile()
{
    return view('Admin.edit_profile'); // Buat file blade baru dengan nama ini
}
  public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = \App\Models\User::find(Auth::id());

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    // PERBAIKAN: Arahkan kembali ke halaman settings/pengaturan
    // Dan sertakan pesan 'success'
    return redirect()->route('settings.show')->with('success', 'Password berhasil diubah!');
}

    public function createKelasForm() { return view('Dosen.tambah_kelas_form'); }
    
    public function pantauAbsensi($id) {
    $sesi = SesiAbsensi::with('kelas')->findOrFail($id);
    // Sesuaikan dengan lokasi file Anda yang sekarang
    return view('dosen.show_qr', compact('sesi')); 
}

    public function rekapAbsensi() { 
        $rekap = Absensi::where('mahasiswa_id', Auth::id())->get(); 
        return view('mahasiswa.rekap', compact('rekap')); 
    }

    public function rekapDosen() { 
        $rekap = Absensi::with('user')->get(); 
        return view('Dosen.rekap', compact('rekap')); 
    }

    public function tambahKelas() { return view('Dosen.tambah_kelas_form'); }

 public function storeSesi(Request $request) {
    $request->validate([
        'durasi' => 'required|numeric',
        'radius' => 'required|numeric',
    ]);

    $sesi = SesiAbsensi::create([
    'kelas_id'      => $request->kelas_id,
    'qr_token'      => \Illuminate\Support\Str::random(40),
    'latitude'      => $request->latitude,
    'longitude'     => $request->longitude,
    'radius'        => $request->radius,
    'is_active'     => 1, // Sesuai kolom di DB
    'waktu_mulai'   => now(),
    'waktu_selesai' => now()->addMinutes((int)$request->durasi), // Sesuai kolom di DB
]);

    return redirect()->route('dosen.absensi.pantau', ['id' => $sesi->id]);
}

public function exportExcel()
{
    return Excel::download(new AbsensiExport, 'rekap-absensi.xlsx');
}

public function store(Request $request) 
{
    // Gunakan nama kolom yang benar sesuai database
    SesiAbsensi::create([
        'kelas_id'      => $request->kelas_id,
        'qr_token'      => Str::random(40),
        'latitude'      => $request->latitude,
        'longitude'     => $request->longitude,
        'radius'        => $request->radius,
        'is_active'     => 1, // Pengganti kolom 'status'
        'waktu_mulai'   => now(),
        'waktu_selesai' => now()->addMinutes($request->durasi), // Pengganti 'expired_at'
    ]);

    return redirect()->back()->with('success', 'Sesi absensi berhasil dibuka.');
}

public function exportExcelMahasiswa()
{
    // Ini adalah kode asli untuk mendownload file
    return Excel::download(new MahasiswaAbsensiExport, 'data-mahasiswa.xlsx');
}
// =========================================================================
    // FITUR SCAN ABSENSI MAHASISWA (GEOFENCING + TOKEN + WAKTU)
    // =========================================================================
    
    public function scanAbsensi(Request $request)
    {
        // 1. Validasi input yang dikirim oleh scanner/kamera mahasiswa
        $request->validate([
            'qr_token'  => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 2. Cari data Mahasiswa berdasarkan User ID yang sedang login
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', Auth::id())->first();
        if (!$mahasiswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data mahasiswa tidak ditemukan di sistem.'
            ], 404);
        }

        // 3. Validasi Parameter 1: Cek apakah Token QR aktif dan ada di database
        $sesi = SesiAbsensi::where('qr_token', $request->qr_token)
                           ->where('is_active', 1) // atau true, sesuaikan database kamu
                           ->first();

        if (!$sesi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR Code tidak valid atau sesi absensi telah ditutup.'
            ], 403);
        }

        // Ambil data kelas untuk mendapatkan koordinat lokasi yang diset dosen
        $kelas = $sesi->kelas; 
        $now = \Carbon\Carbon::now();

        // 4. Validasi Parameter 2: Cek apakah waktu scan masih dalam rentang sesi kuliah
        $validWaktu = $now->between($sesi->waktu_mulai, $sesi->waktu_selesai);
        
        // Tentukan status kehadiran (Hadir vs Terlambat) dengan toleransi bawaan (default: 15 menit)
        $status = 'hadir';
        $batasTerlambat = \Carbon\Carbon::parse($sesi->waktu_mulai)->addMinutes($sesi->toleransi_menit ?? 15);
        if ($now->gt($batasTerlambat)) {
            $status = 'terlambat';
        }

        // 5. Validasi Parameter 3: Cek Lokasi Mahasiswa (Geofencing) dari koordinat SESI yang dibuat dosen
        $jarak = $this->calculateDistance(
            $request->latitude, $request->longitude,
            $sesi->latitude, $sesi->longitude
        );
        
        // Menggunakan aturan radius yang tersimpan di tabel sesi_absensi
        $validLokasi = $jarak <= $sesi->radius;

        // 6. Cek Duplikasi: Memastikan mahasiswa tidak men-scan dua kali di sesi yang sama
        $sudahAbsen = Absensi::where('sesi_id', $sesi->id) // sesuaikan jika nama kolomnya 'sesi_absensi_id'
                             ->where('mahasiswa_id', $mahasiswa->id)
                             ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan presensi pada sesi ini.'
            ], 400);
        }

        // 7. Simpan Riwayat Absensi ke dalam Database (Paling Aman untuk Enum Status)
        Absensi::create([
            'sesi_id'      => $sesi->id, 
            'mahasiswa_id' => $mahasiswa->id,
            'scan_at'      => $now,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'jarak_meter'  => $jarak,
            // Jika lokasi/waktu salah, status kita set 'alpha' agar lolos validasi ENUM MySQL
            'status'       => ($validWaktu && $validLokasi) ? $status : 'alpha',
        ]);

        // 8. Berikan respon balik berupa JSON ke AJAX/Javascript di halaman web
        if (!$validLokasi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Presensi Gagal: Anda berada di luar radius kelas (Jarak: ' . round($jarak) . ' meter).'
            ], 403);
        }

        if (!$validWaktu) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Presensi Gagal: Sesi absensi belum dimulai atau sudah berakhir.'
            ], 403);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Presensi Berhasil dicatat sebagai ' . ucfirst($status) . '! (Jarak: ' . round($jarak) . ' meter)'
        ], 200);
    }

    /**
     * Fungsi Pendukung: Algoritma Haversine untuk menghitung jarak koordinat bumi (Meter)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Jari-jari bumi dalam satuan meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}