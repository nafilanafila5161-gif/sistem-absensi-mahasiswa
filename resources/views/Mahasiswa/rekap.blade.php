@extends('layouts.admin')

@section('content')
<!-- Tambahan Style Premium - Riwayat Absensi Mahasiswa Cyber Navy Engineering -->
<style>
    /* Efek blueprint mesh grid pada latar belakang halaman */
    .container-custom-history {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Kartu Panel Utama dengan Glow Top-Bar Khas Cyber Navy */
    .card-tech-history {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        background: #ffffff;
        overflow: hidden;
    }
    .card-tech-history::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
    }

    /* Header Modul Kelas */
    .header-history-tech {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 16px 20px !important;
    }

    /* Kustomisasi Tabel Riwayat */
    .table-history-tech {
        vertical-align: middle !important;
    }
    .table-history-tech thead th {
        background: #0a192f !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px !important;
        border: none !important;
    }
    .table-history-tech tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-history-tech tbody tr:hover {
        background-color: rgba(23, 70, 148, 0.02) !important;
    }
    .table-history-tech tbody td {
        padding: 12px 14px !important;
        color: #334155 !important;
        font-weight: 500;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    /* Tombol Taktis Download Laporan */
    .btn-tech-emerald {
        background: linear-gradient(135deg, #065f46 0%, #0f766e 100%) !important;
        border: none !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-tech-emerald:hover {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%) !important;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        transform: translateY(-0.5px);
        color: #ffffff !important;
    }

    /* Desain Badges Status Mahasiswa */
    .badge-status-hadir {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
        border: 1px solid rgba(6, 95, 70, 0.15) !important;
        font-weight: 600;
        padding: 5px 12px !important;
    }
    .badge-status-warning {
        background-color: #fffbeb !important;
        color: #92400e !important;
        border: 1px solid rgba(146, 64, 14, 0.15) !important;
        font-weight: 600;
        padding: 5px 12px !important;
    }
</style>

@section('content')
<div class="container-fluid container-custom-history py-4">
    <div class="mb-4">
        <h3 class="fw-bold m-0" style="color: #0a192f;">
            <i class="bi bi-clock-history me-2" style="color: #0f2b5c;"></i>Riwayat Absensi Saya
        </h3>
        <p class="text-muted small mb-0">Pantau daftar kehadiran log presensi Anda secara transparan per mata kuliah yang diambil.</p>
    </div>

    {{-- 1. Pengelompokan Data Absensi Per Kelas_ID --}}
    @forelse($rekap->groupBy('sesi.kelas_id') as $kelasId => $daftarAbsen)
        @php 
            // Mengambil info kelas dari data pertama dalam grup ini
            $infoKelas = $daftarAbsen->first()->sesi->kelas; 
        @endphp

        <div class="card card-tech-history mb-4">
            <div class="card-header header-history-tech d-flex flex-sm-row justify-content-between align-items-sm-center gap-2.5 py-3">
                <h6 class="m-0 fw-bold d-flex align-items-center gap-2" style="color: #0a192f;">
                    <i class="bi bi-journal-text text-secondary"></i>
                    
                    {{-- Mengambil nama_mk langsung dari tabel kelas --}}
                    {{ $infoKelas->nama_mk ?? 'Mata Kuliah' }} 
                    
                    <span class="badge bg-light text-secondary border font-monospace px-2 py-0.5" style="font-size: 0.8rem;">
                        {{ $infoKelas->kode_kelas ?? '-' }}
                    </span>
                </h6>

                <a href="/mahasiswa/export-semester/{{ $kelasId }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 fw-semibold px-3 py-1.5 shadow-sm rounded-2">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            <div class="card-body px-4 pb-4 pt-3">
                <div class="row row-gap-2 mb-3 bg-light p-2.5 rounded-3 border border-light-subtle text-muted small fw-medium mx-0">
                    
                    <div class="col-sm-4 col-md-3">
                        <i class="bi bi-calendar3 me-1.5 text-secondary"></i>
                        <strong>Hari:</strong> {{ $infoKelas->hari ?? '-' }}
                    </div>
                    
                    <div class="col-sm-4 col-md-3">
                        <i class="bi bi-bookmark-star me-1.5 text-secondary"></i>
                        <strong>Beban:</strong> {{ $infoKelas->sks ?? '-' }} SKS
                    </div>
                    
                    <div class="col-sm-4 col-md-4">
                        <i class="bi bi-check2-circle me-1.5 text-success"></i>
                        <strong>Total Kehadiran:</strong> 
                        <span class="text-dark fw-bold">{{ $daftarAbsen->count() }} Pertemuan</span>
                    </div>
                    
                </div>

                <div class="table-responsive rounded-3 border overflow-hidden shadow-sm">
                    <table class="table table-hover table-history-tech m-0" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">No</th>
                                <th>Tanggal Perkulihan</th>
                                <th>Jam Waktu Scan</th>
                                <th class="text-center" style="width: 140px;">Status Mandiri</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarAbsen as $index => $item)
                            <tr>
                                <td class="text-center text-secondary font-monospace">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">
                                    <i class="bi bi-calendar-check me-1.5 text-muted small"></i>{{ \Carbon\Carbon::parse($item->scan_at)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-clock text-muted small"></i> {{ \Carbon\Carbon::parse($item->scan_at)->format('H:i') }} <span class="text-muted small">WIB</span>
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'hadir')
                                        <span class="badge rounded-pill badge-status-hadir">
                                            <i class="bi bi-patch-check-fill me-1"></i> Hadir
                                        </span>
                                    @else
                                        <span class="badge rounded-pill badge-status-warning">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div> </div> @empty
        {{-- Tampilan Placeholder Kosong --}}
        <div class="alert alert-info text-center py-5 border-0 shadow-sm rounded-4" style="background-color: #f0fdfa; color: #0f766e; border-left: 5px solid #0f766e !important;">
            <div class="mb-2">
                <i class="bi bi-clipboard-x text-muted" style="font-size: 3.5rem;"></i>
            </div>
            <h6 class="fw-bold mb-1">Belum Ada Catatan Riwayat</h6>
            <p class="small mb-0 opacity-75">Sistem belum menemukan adanya rekaman log aktivitas absensi atas akun Anda.</p>
        </div>
    @endforelse
</div> @endsection