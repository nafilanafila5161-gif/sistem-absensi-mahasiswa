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
        // Mengambil ID akun yang sedang login secara mutlak
        $id_user = Auth::id();

        // Mencari kelas yang kolom dosen_id-nya berisi angka ID user tersebut
        $daftar_kelas = Kelas::where('dosen_id', $id_user)->get();

        // Kirim variabel ke view dashboard
        return view('dosen.dashboard', compact('daftar_kelas'));
    }

    /**
     * Membuat sesi absensi baru (Buka Absensi via AJAX Pop-up)
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

        // Membuat data sesi absensi baru
        $sesi = SesiAbsensi::create([
            'kelas_id'      => $request->kelas_id,
            'qr_token'      => Str::random(40),
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'radius'        => $request->radius,
            'is_active'     => 1,
            'waktu_mulai'   => now(),
            'waktu_selesai' => now()->addMinutes((int)$request->durasi),
        ]);

        // FIXED: Mengembalikan respon JSON untuk dibaca oleh AJAX Javascript di halaman view
        return response()->json([
            'status'   => 'success',
            'qr_token' => $sesi->qr_token,
            'nama_mk'  => $sesi->kelas->nama_mk ?? 'Mata Kuliah',
            'kode_mk'  => $sesi->kelas->kode_kelas ?? 'Kode Kelas'
        ]);
    }

    /**
     * Menampilkan QR Code untuk di-scan mahasiswa (Fallback jika dibutuhkan)
     */
    public function showQR($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        $qrcode = QrCode::size(300)->generate($sesi->qr_token);
        return view('dosen.show_qr', compact('qrcode', 'sesi'));
    }

    /**
     * Menampilkan form tambah kelas
     */
    public function createKelas()
    {
        return view('dosen.kelas.create');
    }

    /**
     * Menyimpan data kelas baru ke database
     */
    public function storeKelas(Request $request)
    {
        // 1. Validasi input dari form HTML
        $request->validate([
            'nama_mk'     => 'required|string|max:255',
            'kode_kelas'  => 'required|string|max:50', 
            'hari'        => 'required|string|max:20',
            'jam_mulai'   => 'required', 
            'jam_selesai' => 'required', 
            'sks'         => 'required|integer',
        ]);

        // 2. Mengambil ID akun login langsung
        $id_user = Auth::id();

        // 3. Simpan ke database
        Kelas::create([
            'dosen_id'    => $id_user, 
            'nama_mk'     => $request->nama_mk,
            'kode_kelas'  => $request->kode_kelas,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,   
            'jam_selesai' => $request->jam_selesai, 
            'sks'         => $request->sks,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Form Edit Kelas
     */
    public function editKelas($id)
    {
        // Ambil ID User login secara langsung
        $id_user = Auth::id();
        
        // Cari kelas berdasarkan dosen_id yang berisi ID User tersebut
        $kelas = Kelas::where('dosen_id', $id_user)->findOrFail($id);
        
        // Kembalikan ke halaman edit dengan membawa data kelas
        return view('dosen.kelas.edit', compact('kelas'));
    }

    /**
     * Memperbarui data kelas
     */
    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama_mk'     => 'required|string|max:255',
            'kode_kelas'  => 'required|string|max:50', 
            'hari'        => 'required|string|max:20',
            'jam_mulai'   => 'required', 
            'jam_selesai' => 'required', 
            'sks'         => 'required|integer',
        ]);

        $kelas = Kelas::findOrFail($id);
        
        $kelas->update([
            'nama_mk'     => $request->nama_mk,
            'kode_kelas'  => $request->kode_kelas,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,   
            'jam_selesai' => $request->jam_selesai, 
            'sks'         => $request->sks,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Membuka form absensi
     */
    public function bukaAbsen($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('dosen.buka_absen', compact('kelas'));
    }

    /**
     * Mengunduh rekap absensi ke file Excel
     */
    public function exportExcel($id)
    {
        return Excel::download(new AbsensiExport($id), 'rekap_absensi_kelas_'.$id.'.xlsx');
    }

    /**
     * Menampilkan halaman Rekap Absensi di Sisi Dosen
     */
    public function rekapDosen()
    {
        $id_user = Auth::id();

        // Validasi strict ke relasi tabel absensi
        $rekap = Absensi::with(['sesi.kelas', 'user'])
            ->whereHas('sesi', function($query) {
                $query->whereNotNull('kelas_id');
            })
            ->whereHas('sesi.kelas', function($query) use ($id_user) {
                $query->where('dosen_id', $id_user);
            })
            ->get();

        return view('dosen.rekap', compact('rekap'));
    }

    /**
     * Fungsi hapus kelas
     */
    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }

    /**
     * Melakukan eksport data per semester (SUDAH DIPERBAIKI LANGSUNG DOWNLOAD EXCEL)
     */
    public function exportSemester($id) 
    {
        $data = Absensi::with(['sesi.kelas', 'user'])
            ->whereHas('sesi', function($q) use ($id) {
                $q->where('kelas_id', $id);
            })
            ->whereHas('sesi.kelas')
            ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Belum ada data absensi untuk kelas ini.');
        }

        return Excel::download(new \App\Exports\AbsensiExport($id), 'rekap_absensi_semester_kelas_'.$id.'.xlsx');
    }

    /**
     * Mengunduh rekap absensi per pertemuan tertentu
     */
    public function exportPertemuan($id)
    {
        return Excel::download(new \App\Exports\AbsensiExport($id), 'rekap_absen_pertemuan_'.$id.'.xlsx');
    }
}