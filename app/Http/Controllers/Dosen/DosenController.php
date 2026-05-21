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
use App\Models\Absensi;

class DosenController extends Controller
{
    
    /**
     * Menampilkan daftar kelas yang diampu dosen
     */
   public function index()
{
    // Mengambil ID akun yang sedang login secara mutlak (Nilainya = 2)
    $id_user = \Illuminate\Support\Facades\Auth::id();

    // Mencari kelas yang kolom dosen_id-nya berisi angka ID user tersebut
    $daftar_kelas = \App\Models\Kelas::where('dosen_id', $id_user)->get();

    // Kirim variabel ke view dashboard
    return view('dosen.dashboard', compact('daftar_kelas'));
}

    /**
     * Membuat sesi absensi baru (Buka Absensi)
     */
   public function storeSesi(Request $request)
{
    $request->validate([
        'kelas_id' => 'required',
        'durasi'   => 'required|integer',
        'radius'   => 'required|integer',
        'latitude' => 'required',
        'longitude'=> 'required',
    ]);

    // LANGSUNG BUAT SESI TANPA PENGECEKAN JAM KULIAH LAMA
    $sesi = \App\Models\SesiAbsensi::create([
        'kelas_id'      => $request->kelas_id,
        'qr_token'      => \Illuminate\Support\Str::random(40),
        'latitude'      => $request->latitude,
        'longitude'     => $request->longitude,
        'radius'        => $request->radius,
        'is_active'     => 1,
        'waktu_mulai'   => now(),
        'waktu_selesai' => now()->addMinutes((int)$request->durasi),
    ]);

    // Arahkan ke fungsi showQR
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
        return view('dosen.kelas.create');
    }

    // Menyimpan data kelas baru ke database
  public function storeKelas(Request $request)
{
    
    // 1. Validasi input dari form HTML
    $request->validate([
        'nama_mk'     => 'required|string|max:255',
        'kode_kelas'  => 'required|string|max:50', 
        'hari'        => 'required|string|max:20',
        'jam_mulai'   => 'required', // Pastikan form mengirim ini
        'jam_selesai' => 'required', // Pastikan form mengirim ini
        'sks'         => 'required|integer',
    ]);

    // 2. Mengambil ID akun login langsung
    $id_user = \Illuminate\Support\Facades\Auth::id();

    // 3. Simpan ke database (WAJIB masukkan jam_mulai dan jam_selesai di sini)
    \App\Models\Kelas::create([
        'dosen_id'    => $id_user, 
        'nama_mk'     => $request->nama_mk,
        'kode_kelas'  => $request->kode_kelas,
        'hari'        => $request->hari,
        'jam_mulai'   => $request->jam_mulai,   // <-- BARIS INI WAJIB ADA
        'jam_selesai' => $request->jam_selesai, // <-- BARIS INI WAJIB ADA
        'sks'         => $request->sks,
    ]);

    return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil dibuat.');
}

    // Form Edit Kelas (Jika ada perubahan lokasi atau jadwal)
    public function editKelas($id)
{
    // Ambil ID User login secara langsung (Nilainya = 2)
    $id_user = \Illuminate\Support\Facades\Auth::id();
    
    // Cari kelas berdasarkan dosen_id yang berisi ID User tersebut
    $kelas = \App\Models\Kelas::where('dosen_id', $id_user)->findOrFail($id);
    
    // Kembalikan ke halaman edit dengan membawa data kelas
    return view('dosen.kelas.edit', compact('kelas'));
}

public function updateKelas(Request $request, $id)
{
    $request->validate([
        'nama_mk'     => 'required|string|max:255',
        'kode_kelas'  => 'required|string|max:50', 
        'hari'        => 'required|string|max:20',
        'jam_mulai'   => 'required', // Tambahkan validasi jam
        'jam_selesai' => 'required', // Tambahkan validasi jam
        'sks'         => 'required|integer',
    ]);

    $kelas = \App\Models\Kelas::findOrFail($id);
    
    $kelas->update([
        'nama_mk'     => $request->nama_mk,
        'kode_kelas'  => $request->kode_kelas,
        'hari'        => $request->hari,
        'jam_mulai'   => $request->jam_mulai,   // Update jam mulai
        'jam_selesai' => $request->jam_selesai, // Update jam selesai
        'sks'         => $request->sks,
    ]);

    return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil diperbarui.');
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
        return Excel::download(new AbsensiExport($id), 'rekap_absensi_kelas_'.$id.'.xlsx');
    }

    // Menampilkan halaman Rekap Absensi di Sisi Dosen
    public function rekapDosen()
    {
        // PERBAIKAN BARIS 132: Menggunakan Auth::id() agar tidak digarismerahi VS Code
        $id_user = Auth::id();

        // Ambil data absensi
        $rekap = \App\Models\Absensi::with(['sesi.kelas', 'user'])
            ->whereHas('sesi.kelas', function($query) use ($id_user) {
                $query->where('dosen_id', $id_user);
            })
            ->get();

        return view('dosen.rekap', compact('rekap'));
    }

    // Fungsi hapus kelas
    public function destroyKelas($id)
    {
        $kelas = \App\Models\Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }

    public function exportSemester($id) 
    {
        // Ambil data absensi berdasarkan Kelas (Satu Semester) tanpa .mataKuliah gaib
        $data = Absensi::with(['sesi.kelas', 'user'])
            ->whereHas('sesi', function($q) use ($id) {
                $q->where('kelas_id', $id);
            })
            ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Belum ada data absensi untuk kelas ini.');
        }

        return view('dosen.export_excel', compact('data'));
    }
}