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
        
        // Mengambil log absensi mahasiswa
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
        // 1. Ambil data model Mahasiswa berdasarkan akun user yang sedang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // 2. Ambil data absensi HANYA milik mahasiswa ini di KELAS tersebut
        // Mengubah 'user_id' menjadi 'mahasiswa_id' agar singkron dengan database
        $data = Absensi::with(['sesi.kelas.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswa->id) 
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