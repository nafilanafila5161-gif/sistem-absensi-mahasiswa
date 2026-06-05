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

    // 2. Ambil data absensi milik mahasiswa di kelas tersebut
    $data = Absensi::with(['sesi.kelas'])
        ->where('mahasiswa_id', $mahasiswa->id) 
        ->whereHas('sesi', function($q) use ($id_kelas) {
            $q->where('kelas_id', $id_kelas);
        })
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Riwayat absensi tidak ditemukan.');
    }

    // 3. AMBIL DATA UNTUK HEADER EXCEL
    $info = $data->first()->sesi->kelas;
    $namaMataKuliah = $info->nama_mk ?? 'Mata Kuliah';
    $kodeKelas = $info->kode_kelas ?? '-';
    $hariKelas = $info->hari ?? '-';
    $sksKelas = $info->sks ?? '-';

    // 4. TRICK BYPASS: REKAYASA HEADER BROWSER LANGSUNG MENJADI EXCEL
    $namaFile = "Riwayat_Absen_" . str_replace(' ', '_', $namaMataKuliah) . ".xls";
    
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$namaFile");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 5. PRINT STRUKTUR TABEL HTML LANGSUNG DARI CONTROLLER (TANPA FILE BLADE)
    echo "
    <h3>RIWAYAT ABSENSI MAHASISWA</h3>
    <table style='margin-bottom: 15px;'>
        <tr><td><strong>Mata Kuliah</strong></td><td>: $namaMataKuliah ($kodeKelas)</td></tr>
        <tr><td><strong>Hari / SKS</strong></td><td>: $hariKelas / $sksKelas SKS</td></tr>
    </table>

    <table border='1' style='border-collapse: collapse; width: 100%;'>
        <thead>
            <tr style='background-color: #f2f2f2; font-weight: bold;'>
                <th style='padding: 6px; text-align: center; width: 50px;'>No</th>
                <th style='padding: 6px; text-align: left;'>Tanggal Perkuliahan</th>
                <th style='padding: 6px; text-align: left;'>Jam Scan</th>
                <th style='padding: 6px; text-align: center;'>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>";

        foreach($data as $index => $item) {
            $no = $index + 1;
            $tanggal = \Carbon\Carbon::parse($item->scan_at)->format('d/m/Y');
            $jam = \Carbon\Carbon::parse($item->scan_at)->format('H:i') . " WIB";
            $status = ucfirst($item->status);

            echo "
            <tr>
                <td style='padding: 6px; text-align: center;'>$no</td>
                <td style='padding: 6px;'>$tanggal</td>
                <td style='padding: 6px;'>$jam</td>
                <td style='padding: 6px; text-align: center;'>$status</td>
            </tr>";
        }

    echo "
        </tbody>
    </table>";
    exit;
}
}