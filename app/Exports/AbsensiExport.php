<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $kelas_id;

    public function __construct($kelas_id) {
        $this->kelas_id = $kelas_id;
    }

    public function collection() {
        return Absensi::whereHas('sesi', function($q) {
            $q->where('kelas_id', $this->kelas_id);
        })->get(['mahasiswa_id', 'scan_at', 'status', 'jarak_meter']);
    }

    public function headings(): array {
        return ['ID Mahasiswa', 'Waktu Presensi', 'Status Kehadiran', 'Jarak Scan (Meter)'];
    }
}