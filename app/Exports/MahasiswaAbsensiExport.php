<?php
namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MahasiswaAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Filter data: Hanya ambil absensi milik mahasiswa yang sedang login
        return Absensi::with(['sesi.kelas.dosen'])
            ->where('mahasiswa_id', Auth::id()) 
            ->get();
    }

    public function headings(): array
    {
        // Menambahkan kolom Hari dan SKS di bagian judul
        return ['Waktu Absen', 'Hari', 'Mata Kuliah', 'SKS', 'Dosen', 'Status'];
    }

    public function map($data): array
    {
        return [
            $data->created_at->format('d/m/Y H:i'),
            $data->sesi->hari ?? '-', // Menambahkan data Hari
            $data->sesi->kelas->nama_mk ?? '-',
            $data->sesi->kelas->sks ?? '-', // Menambahkan data SKS
            $data->sesi->kelas->dosen->name ?? '-', 
            'Hadir'
        ];
    }
}