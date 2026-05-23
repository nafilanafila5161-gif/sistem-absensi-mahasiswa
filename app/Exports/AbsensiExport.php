<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil data absensi dengan relasi lengkap ke tabel mahasiswa, sesi, kelas, dan dosen
        return Absensi::with(['mahasiswa', 'sesi.kelas.dosen'])->get();
    }

    // Mengatur judul kolom di file Excel
    public function headings(): array
    {
        return [
            'Nama Dosen',
            'Kode Kelas',
            'Mata Kuliah',
            'Hari',
            'Jam Mulai',
            'Durasi (Menit)',
            'Nama Mahasiswa',
            'Waktu Absen',
            'Status'
        ];
    }

    // Memetakan data dari database ke kolom Excel yang sesuai
    public function map($data): array
    {
        // PERBAIKAN AMAN: Mengubah string waktu menjadi objek Carbon sebelum di-format
        $waktuMulai = \Carbon\Carbon::parse($data->sesi->waktu_mulai);
        $waktuSelesai = \Carbon\Carbon::parse($data->sesi->waktu_selesai);
        
        // Format waktu absen saat mahasiswa melakukan scanning
        $waktuAbsen = $data->scan_at ? \Carbon\Carbon::parse($data->scan_at)->format('d-m-Y H:i') : '-';

        return [
            $data->sesi->kelas->dosen->name ?? '-',                      // Nama Dosen
            $data->sesi->kelas->kode_kelas ?? '-',                       // Kode Kelas
            $data->sesi->kelas->nama_mk ?? '-',                          // Nama Mata Kuliah
            $waktuMulai->translatedFormat('l'),                          // Hari (Senin, Selasa, dsb)
            $waktuMulai->format('H:i'),                                  // Jam Mulai
            $waktuMulai->diffInMinutes($waktuSelesai),                   // Durasi dalam menit
            $data->mahasiswa->nama ?? $data->mahasiswa->user->name ?? '-', // Nama Mahasiswa (mencari alternatif aman)
            $waktuAbsen,                                                 // Waktu Absen (Tanggal & Jam scan)
            ucfirst($data->status ?? 'alpha')                            // Status (Hadir, Terlambat, Alpha) dengan huruf kapital depan
        ];
    }
}