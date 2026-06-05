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
    // 1. Ambil data mahasiswa berdasarkan akun user yang sedang login
    $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
    
    // Antisipasi jika data mahasiswa tidak ditemukan di database agar aplikasi tidak crash
    if (!$mahasiswa) {
        $rekap = collect(); 
        return view('Mahasiswa.rekap', compact('rekap'));
    }
    
    // 2. Ambil log absensi (Ubah nama variabel dari $riwayat menjadi $rekap agar sesuai dengan isi file rekap.blade.php)
    $rekap = Absensi::where('mahasiswa_id', $mahasiswa->id)
                    ->with('sesi.kelas.mataKuliah')
                    ->orderBy('scan_at', 'desc')
                    ->get();

    // 3. Ubah tujuan view dari 'mahasiswa.riwayat' menjadi 'Mahasiswa.rekap' 
    // Sesuaikan huruf besar/kecil (Capital Case) nama folder di Laravel Anda ("Mahasiswa")
    return view('Mahasiswa.rekap', compact('rekap'));
}


public function rekap()
{
    // 1. Ambil data mahasiswa berdasarkan user yang login
    $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

    if (!$mahasiswa) {
        $rekap = collect();
        return view('Mahasiswa.rekap', compact('rekap'));
    }

    // 2. Ambil data absensi, cukup hubungkan sampai tabel kelas (jangan panggil mataKuliah)
    $rekap = Absensi::where('mahasiswa_id', $mahasiswa->id)
                    ->with(['sesi', 'sesi.kelas']) 
                    ->orderBy('scan_at', 'desc')
                    ->get();

    // 3. Lempar ke view rekap
    return view('Mahasiswa.rekap', compact('rekap'));
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
    // PERBAIKAN: Menghapus .mataKuliah karena nama_mk ada langsung di tabel kelas
    $data = Absensi::with(['sesi.kelas'])
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