<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Kelas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
public function index()
{
    // Mengambil ID user Nana (ID 2) secara eksplisit
    $id_user = auth()->id();

    // Mengambil semua kelas yang memiliki dosen_id = 2
    $daftar_kelas = \App\Models\Kelas::where('dosen_id', $id_user)->get();

    // Kirim variabel ke view dashboard
    return view('dosen.dashboard', compact('daftar_kelas'));
}

    /**
     * Membuat sesi absensi baru (Buka Absensi)
     */
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

    return redirect()->route('dosen.show_qr', $sesi->id);
}

    /**
     * Menampilkan QR Code untuk di-scan mahasiswa
     */
    public function showQR($id)
{
    $sesi = SesiAbsensi::findOrFail($id);
   $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($sesi->qr_token);
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

// Tambahkan fungsi ini untuk memperbaiki error image_189177.jpg
public function rekapDosen()
{
    $id_user = auth()->id();

    // Pastikan menggunakan whereHas ke 'sesi.kelas' karena tabel absensi tidak punya kelas_id
    $rekap = \App\Models\Absensi::with(['sesi.kelas.mataKuliah', 'user'])
        ->whereHas('sesi.kelas', function($query) use ($id_user) {
            $query->where('dosen_id', $id_user);
        })
        ->get();

    // Variabel $rekap dikirim ke view agar tidak 'Undefined'
    return view('dosen.rekap', compact('rekap'));
}
// Tambahkan juga fungsi hapus jika belum ada
public function destroyKelas($id)
{
    $kelas = \App\Models\Kelas::findOrFail($id);
    $kelas->delete();
    return redirect()->back()->with('success', 'Kelas berhasil dihapus');
}

public function exportSemester($id) 
{
    // 1. Ambil data absensi berdasarkan Kelas (Satu Semester)
    $data = Absensi::with(['sesi.kelas.mataKuliah', 'user'])
        ->whereHas('sesi', function($q) use ($id) {
            $q->where('kelas_id', $id);
        })
        ->get();

    // 2. Cek jika data kosong agar tidak error saat export
    if ($data->isEmpty()) {
        return back()->with('error', 'Belum ada data absensi untuk kelas ini.');
    }

    // 3. Logika export ke Excel (Contoh sederhana)
    // Di sini Anda bisa menggunakan library seperti Maatwebsite/Excel
    // atau sekadar mengembalikan data ke view khusus Excel
    return view('dosen.export_excel', compact('data'));
}

}
