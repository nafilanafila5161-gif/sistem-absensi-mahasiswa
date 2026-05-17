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
        return [
            $data->sesi->kelas->dosen->name ?? '-',      // Nama Dosen
            $data->sesi->kelas->kode_kelas ?? '-',      // Kode Kelas
            $data->sesi->kelas->nama_mk ?? '-',         // Nama Mata Kuliah
            $data->sesi->waktu_mulai->translatedFormat('l'), // Hari (Senin, Selasa, dsb)
            $data->sesi->waktu_mulai->format('H:i'),    // Jam Mulai
            $data->sesi->waktu_mulai->diffInMinutes($data->sesi->waktu_selesai), // Durasi
            $data->user->name ?? $data->mahasiswa->nama, // Nama Mahasiswa
            $data->created_at->format('d/m/Y H:i'),     // Waktu Absen
            'Hadir'                                     // Status
        ];
    }
}