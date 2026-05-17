<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\SesiAbsensi;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;


class DosenController extends Controller
{
    
    /**
     * Menampilkan daftar kelas yang diampu dosen
     */
   public function index(){
    $dosenId = Auth::user()->dosen->id;
    $daftar_kelas = Kelas::with('mataKuliah')->where('dosen_id', $dosenId)->get();
    
    // Gunakan 'daftar_kelas' agar sesuai dengan file blade dashboard sebelumnya
    return view('dosen.dashboard', compact('daftar_kelas'));
}

    /**
     * Membuat sesi absensi baru (Buka Absensi)
     */
    public function storeSesi(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'menit_aktif' => 'required|integer', // Durasi absensi dibuka
            'materi' => 'nullable|string'
        ]);

        // Generate Token QR Unik[cite: 1, 2]
        $qrToken = Str::random(40); 
        
        $sesi = SesiAbsensi::create([
            'kelas_id' => $request->kelas_id,
            'qr_token' => $qrToken,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => Carbon::now()->addMinutes($request->menit_aktif),
            'is_active' => true,
            'materi' => $request->materi
        ]);

        return redirect()->route('dosen.show_qr', $sesi->id);
    }

    /**
     * Menampilkan QR Code untuk di-scan mahasiswa
     */
    public function showQR($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        
        // Cek apakah sesi masih berlaku secara waktu
        if (Carbon::now()->gt($sesi->waktu_selesai)) {
            $sesi->update(['is_active' => false]);
        }

        // Generate QR Code menggunakan library simplesoftwareio/simple-qrcode
        $qrcode = QrCode::size(400)->generate($sesi->qr_token);

        return view('dosen.show_qr', compact('qrcode', 'sesi'));
    }

    // Menampilkan form tambah kelas
public function createKelas()
{
    // Mengambil data mata kuliah untuk dropdown (berdasarkan skema database)
    $mata_kuliah = \App\Models\MataKuliah::all(); 
    return view('dosen.kelas.create', compact('mata_kuliah'));
}

// Menyimpan data kelas baru ke database
public function storeKelas(Request $request)
{
    $request->validate([
        'mata_kuliah_id' => 'required',
        'kode_kelas' => 'required|unique:kelas',
        'hari' => 'required',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'radius_meter' => 'required|integer',
    ]);

    \App\Models\Kelas::create([
        'dosen_id' => Auth::user()->dosen->id,
        'mata_kuliah_id' => $request->mata_kuliah_id,
        'kode_kelas' => $request->kode_kelas,
        'hari' => $request->hari,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'radius_meter' => $request->radius_meter,
        'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
    ]);

    return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil dibuat.');
}

// Form Edit Kelas (Jika ada perubahan lokasi atau jadwal)
public function editKelas($id)
{
    $kelas = \App\Models\Kelas::where('dosen_id', Auth::user()->dosen->id)->findOrFail($id);
    $mata_kuliah = \App\Models\MataKuliah::all();
    return view('dosen.kelas.edit', compact('kelas', 'mata_kuliah'));
}

public function bukaAbsen($id)
{
    $kelas = \App\Models\Kelas::findOrFail($id);
    return view('dosen.buka_absen', compact('kelas'));
}

/**
 * Mengunduh rekap absensi ke file Excel
 */
public function exportExcel($id)
{
    // Pastikan $id adalah ID Kelas
    return Excel::download(new AbsensiExport($id), 'rekap_absensi_kelas_'.$id.'.xlsx');
}

}
