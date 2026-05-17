<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        // Mengambil data mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        
        // Mengambil daftar kelas yang diikuti mahasiswa tersebut (tabel kelas_mahasiswa)
        $kelas = $mahasiswa->kelas; 

        return view('mahasiswa.dashboard', compact('kelas'));
    }

    public function riwayat()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        
        // Mengambil log absensi mahasiswa[cite: 1]
        $riwayat = Absensi::where('mahasiswa_id', $mahasiswa->id)
                            ->with('sesi.kelas.mataKuliah')
                            ->orderBy('scan_at', 'desc')
                            ->get();

        return view('mahasiswa.riwayat', compact('riwayat'));
    }
}