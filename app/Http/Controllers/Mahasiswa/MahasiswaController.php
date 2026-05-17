<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Exports\MahasiswaAbsensiExport;

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

    public function exportExcel()
{
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MahasiswaAbsensiExport, 'riwayat-absensi.xlsx');
}

public function exportSemesterMahasiswa($id_kelas) 
{
    $id_user = auth()->id(); // Ambil ID mahasiswa yang sedang login

    // Ambil data absensi HANYA milik mahasiswa ini di KELAS tersebut
    $data = Absensi::with(['sesi.kelas.mataKuliah'])
        ->where('user_id', $id_user) // Filter agar tidak melihat absen orang lain
        ->whereHas('sesi', function($q) use ($id_kelas) {
            $q->where('kelas_id', $id_kelas);
        })
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Riwayat absensi tidak ditemukan.');
    }

    // Proses export...
    return view('mahasiswa.export_excel', compact('data'));
}
}