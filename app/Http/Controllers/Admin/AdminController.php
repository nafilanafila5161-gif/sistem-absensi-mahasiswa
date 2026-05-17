<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Str;
use App\Models\SesiAbsensi;

class AdminController extends Controller
{
    // FIX: Fungsi login dengan pendefinisian $credentials
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            // Redirect otomatis: admin/dashboard, dosen/dashboard, atau mahasiswa/dashboard
            return redirect()->intended($role . '/dashboard'); 
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

   

   public function index()
{
    $user = Auth::user();
    
    // Ambil data dosen dari user yang login
    $dosen = $user->dosen; 

    // Jika dosen ditemukan, ambil kelasnya. Jika tidak, set total 0.
    if ($dosen) {
        $daftar_kelas = \App\Models\Kelas::where('dosen_id', $dosen->id)->get();
        $total_kelas = $daftar_kelas->count();
    } else {
        $daftar_kelas = collect(); // Koleksi kosong
        $total_kelas = 0;
    }

    return view('Dosen.dashboard', compact('total_kelas', 'daftar_kelas'));
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('Admin.manage_users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:dosen,mahasiswa',
        ]);

        $plainPassword = Str::random(8); 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($plainPassword),
        ]);

        Mail::to($user->email)->send(new UserCreatedMail($user, $plainPassword));
        return back()->with('success', 'User berhasil didaftarkan!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function profile()
    {
        $user = Auth::user(); 
        return view('auth.profile', compact('user'));
    }

    // Tambahkan di dalam class AdminController
public function settingsShow()
{
    // Pastikan Anda punya file resources/views/settings.blade.php
    return view('auth.settings'); 
}

    public function showForgotPassword()
{
    return view('auth.forgot-password');
}

public function updatePassword(Request $request)
{
    // Logika simpan password baru Anda di sini
    $user = \App\Models\User::where('email', $request->email)->first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();
        return redirect()->route('login')->with('success', 'Password berhasil diubah!');
    }
    return back()->withErrors(['email' => 'Email tidak ditemukan']);
}

    public function createKelas()
    {
        return view('Dosen.buka_absen'); 
    }

   public function pantauAbsensi()
{
    // Ambil sesi terbaru atau kirim variabel kosong dulu agar tidak error
    $sesi = \App\Models\SesiAbsensi::latest()->first(); 
    return view('dosen.show_qr', compact('sesi'));
}

public function rekapAbsensi()
{
    // Gunakan 'mahasiswa_id' sesuai gambar struktur database Anda
    $rekap = \App\Models\Absensi::where('mahasiswa_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

    // Pastikan folder 'mahasiswa' menggunakan huruf kecil (image_63fbdb)
    return view('mahasiswa.rekap', compact('rekap')); 
}

public function rekapDosen()
{
    // Gunakan relasi yang benar, pastikan di Model Absensi relasinya bernama 'mahasiswa' atau 'user'
    $rekap = \App\Models\Absensi::with('user') 
                ->orderBy('created_at', 'desc')
                ->get();

    return view('Dosen.rekap', compact('rekap'));
}

public function createKelasForm()
{
    // Ini harus mengarah ke file view baru untuk input data kelas
    return view('dosen.tambah_kelas_form'); 
}



public function storeKelas(Request $request)
{
    $userId = auth()->id();
    $dosen = \App\Models\Dosen::where('user_id', $userId)->first();

    if (!$dosen) {
        return back()->with('error', 'Data Dosen tidak ditemukan.');
    }

    \App\Models\Kelas::create([
        'dosen_id'   => $dosen->id,
        'nama_mk'    => $request->nama_mk,
        'kode_kelas' => $request->kode_kelas,
        'hari'       => $request->hari,
        'sks'        => $request->sks,
    ]);

    return redirect()->route('dosen.dashboard')->with('success', 'Kelas Berhasil Disimpan');
}

public function bukaAbsen($id)
{
    // Mencari data kelas berdasarkan ID yang diklik
    $kelas = \App\Models\Kelas::with('mataKuliah')->findOrFail($id);
    
    // Menampilkan file view yang baru saja Anda buat
    return view('dosen.buka_absen', compact('kelas'));
}

public function tambahKelas()
{
    // Cukup tampilkan view saja tanpa mengirimkan variable $mataKuliah
    return view('Dosen.tambah_kelas_form');
}
public function dashboardDosen()
{
    $userId = auth()->id();
    $dosen = \App\Models\Dosen::where('user_id', $userId)->first();

    if (!$dosen) {
        $kelas = collect();
        return view('dosen.dashboard', compact('kelas'))->with('error', 'Profil belum diatur.');
    }

    $kelas = \App\Models\Kelas::where('dosen_id', $dosen->id)->get();

    return view('dosen.dashboard', compact('kelas'));
}
}