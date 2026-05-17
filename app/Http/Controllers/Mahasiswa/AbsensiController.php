<?php
namespace App\Http\Controllers\Mahasiswa; // Harus sesuai dengan struktur folder
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Fungsi utama untuk mahasiswa melakukan scan QR.
     * Menggunakan multi-validasi: Token, Waktu, dan Geofencing.
     */
    public function scan(Request $request)
    {
        // Validasi input dari request (biasanya dikirim via API/Mobile)
        $request->validate([
            'qr_token'  => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // 1. Identifikasi Mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }

        // 2. VALIDASI PARAMETER 1: QR TOKEN & STATUS AKTIF[cite: 1, 2]
        $sesi = SesiAbsensi::where('qr_token', $request->qr_token)
                            ->where('is_active', true)
                            ->first();

        if (!$sesi) {
            return response()->json(['message' => 'QR Code tidak valid atau sesi telah ditutup.'], 403);
        }

        $kelas = $sesi->kelas; // Relasi ke tabel kelas untuk ambil koordinat dosen
        $now = Carbon::now();

        // 3. VALIDASI PARAMETER 2: RENTANG WAKTU[cite: 1, 2]
        $validWaktu = $now->between($sesi->waktu_mulai, $sesi->waktu_selesai);
        
        // Tentukan status (Hadir vs Terlambat) berdasarkan toleransi (contoh: 15 menit)
        $status = 'hadir';
        $batasTerlambat = Carbon::parse($sesi->waktu_mulai)->addMinutes($sesi->toleransi_menit ?? 15);
        if ($now->gt($batasTerlambat)) {
            $status = 'terlambat';
        }

        // 4. VALIDASI PARAMETER 3: LOKASI (GEOFENCING)
        $jarak = $this->calculateDistance(
            $request->latitude, $request->longitude,
            $kelas->latitude, $kelas->longitude
        );
        
        // Cek apakah jarak mahasiswa <= radius yang ditentukan dosen (misal: 50 meter)[cite: 1, 2]
        $validLokasi = $jarak <= $kelas->radius_meter;

        // 5. PENYIMPANAN LOG ABSENSI[cite: 1, 2]
        $absensi = Absensi::create([
            'sesi_id'      => $sesi->id,
            'mahasiswa_id' => $mahasiswa->id,
            'scan_at'      => $now,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'jarak_meter'  => $jarak,
            'valid_qr'     => true,
            'valid_waktu'  => $validWaktu,
            'valid_lokasi' => $validLokasi,
            // Jika lokasi salah atau waktu habis, status otomatis 'tidak_valid'
            'status'       => ($validWaktu && $validLokasi) ? $status : 'tidak_valid',
            'device_info'  => $request->header('User-Agent')
        ]);

        // Respon balik ke frontend/mobile
        if (!$validLokasi) {
            return response()->json([
                'message' => 'Gagal: Anda berada di luar radius kelas.',
                'jarak'   => round($jarak) . ' meter'
            ], 403);
        }

        if (!$validWaktu) {
            return response()->json(['message' => 'Gagal: Sesi absensi belum dimulai atau sudah berakhir.'], 403);
        }

        return response()->json([
            'message' => 'Absensi berhasil dicatat!',
            'status'  => $status,
            'jarak'   => round($jarak) . ' meter'
        ], 200);
    }

    /**
     * Algoritma Haversine untuk menghitung jarak antara dua koordinat (Meter)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Jari-jari bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}