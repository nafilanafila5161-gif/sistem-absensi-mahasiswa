@extends('layouts.admin')

@section('content')
<style>
    /* Efek blueprint mesh grid pada latar belakang halaman rekap */
    .container-custom-rekap {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Kartu Utama Panel dengan Glow Top-Bar */
    .card-tech-rekap {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        background: #ffffff;
        overflow: hidden;
    }
    .card-tech-rekap::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
    }

    /* Modul Unduh Per Semester (Grid Box) */
    .semester-download-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.25s ease;
    }
    .semester-download-box:hover {
        background-color: #ffffff;
        border-color: #0f2b5c;
        box-shadow: 0 8px 20px rgba(15, 43, 92, 0.06);
        transform: translateY(-1px);
    }

    /* Header Modul Pertemuan Pasca-Group */
    .header-sesi-tech {
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%) !important;
        border-bottom: 2px solid #e2e8f0 !important;
        color: #0a192f !important;
        padding: 14px 20px !important;
    }

    /* Desain Tabel Absensi Premium */
    .table-rekap-tech {
        vertical-align: middle !important;
    }
    .table-rekap-tech thead th {
        background: #0a192f !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px !important;
        border: none !important;
    }
    .table-rekap-tech tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-rekap-tech tbody tr:hover {
        background-color: rgba(23, 70, 148, 0.02) !important;
    }
    .table-rekap-tech tbody td {
        padding: 12px 14px !important;
        color: #334155 !important;
        font-weight: 500;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    /* Tombol Aksi Taktis (Download Excel/Semester) */
    .btn-tech-navy {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        transition: all 0.2s ease;
    }
    .btn-tech-navy:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        box-shadow: 0 4px 12px rgba(15, 43, 92, 0.2);
        color: #ffffff !important;
    }
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
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
        color: #ffffff !important;
    }

    /* Lencana Kode & Status */
    .code-badge-rekap {
        background-color: #0f2b5c;
        color: #ffffff;
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 5px;
    }
    .badge-status-hadir {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
        border: 1px solid rgba(6, 95, 70, 0.15) !important;
        font-weight: 600;
        padding: 5px 12px !important;
    }
    .badge-status-lainnya {
        background-color: #fffbeb !important;
        color: #92400e !important;
        border: 1px solid rgba(146, 64, 14, 0.15) !important;
        font-weight: 600;
        padding: 5px 12px !important;
    }

    /* Pembatas Rapi */
    .hr-tech {
        border-color: rgba(15, 43, 92, 0.1);
        margin: 2.5rem 0;
        opacity: 1;
    }
</style>

<div class="container-fluid container-custom-rekap py-4">
    <div class="mb-4">
        <h3 class="fw-bold m-0" style="color: #0a192f;">
            <i class="bi bi-folder-check me-2" style="color: #0f2b5c;"></i>Manajemen Rekap Absensi
        </h3>
        <p class="text-muted small mb-0">Lakukan pemantauan kehadiran log mahasiswa serta unduh laporan rekapitulasi data absensi di sini.</p>
    </div>

    <div class="card card-tech-rekap mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h6 class="m-0 fw-bold text-uppercase small" style="color: #0f2b5c; letter-spacing: 0.5px;">
                <i class="bi bi-cloud-arrow-down-fill me-2"></i>Download Rekap Per Semester
            </h6>
        </div>
        <div class="card-body px-4 pb-4 pt-2">
            <div class="row row-gap-3">
                @foreach($rekap->unique('sesi.kelas_id') as $data)
                    @php $kelas = $data->sesi->kelas ?? null; @endphp
                    @if($kelas)
                    <div class="col-xl-4 col-md-6">
                        <div class="p-3 semester-download-box shadow-sm">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div class="overflow-hidden">
                                    <div class="mb-1"><span class="code-badge-rekap">{{ $kelas->kode_kelas ?? '-' }}</span></div>
                                    <div class="fw-bold text-dark text-truncate small" style="max-width: 240px;">{{ $kelas->nama_mk ?? 'Tidak Ada Nama MK' }}</div>
                                    <div class="mt-1 d-flex gap-1.5 align-items-center small text-muted">
                                        <span class="badge bg-light text-secondary border px-2 py-1"><i class="bi bi-calendar-event me-1"></i>{{ $kelas->hari ?? '-' }}</span>
                                        <span class="badge bg-light text-info border border-info border-opacity-10 px-2 py-1"><i class="bi bi-bookmark-star me-1"></i>{{ $kelas->sks ?? '0' }} SKS</span>
                                    </div>
                                </div>
                                <a href="{{ route('dosen.rekap.export_semester', ['id' => $kelas->id]) }}" class="btn btn-sm btn-tech-navy p-2.5 d-flex align-items-center justify-content-center" title="Unduh File Semester">
                                    <i class="bi bi-file-earmark-arrow-down fs-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <hr class="hr-tech">

    <h5 class="fw-bold mb-3" style="color: #0a192f;">
        <i class="bi bi-card-list me-2" style="color: #0f2b5c;"></i>Daftar Kehadiran Per Pertemuan
    </h5>
    
    @forelse($rekap->groupBy('sesi_id') as $sesiId => $daftarHadir)
        @php 
            $sesi = $daftarHadir->first()->sesi ?? null;
            $infoKelas = $sesi->kelas ?? null;
        @endphp
        @if($sesi && $infoKelas)
        <div class="card card-tech-rekap mb-4">
            <div class="card-header header-sesi-tech d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <span class="fw-medium">
                    <strong style="color: #0a192f;">{{ $infoKelas->nama_mk ?? 'Tanpa Nama MK' }} ({{ $infoKelas->kode_kelas ?? '-' }})</strong> 
                    <span class="text-muted px-1">|</span> 
                    <span class="small fw-bold text-secondary"><i class="bi bi-clock-history me-1"></i>Pertemuan: {{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('d M Y') }}</span>
                </span>
                <a href="{{ route('dosen.rekap.export_pertemuan', ['id' => $sesiId]) }}" class="btn btn-sm btn-tech-emerald d-inline-flex align-items-center gap-1.5 px-3 py-1.5 shadow-sm">
                    <i class="bi bi-filetype-xls fs-6"></i> Rekap Hari Ini
                </a>
            </div>
            
            <div class="card-body px-4 pb-4 pt-3">
                {{-- Data Informasi Tambahan Meta Sesi --}}
                <div class="row row-gap-2 mb-3 bg-light p-2.5 rounded-3 border border-light-subtle text-muted small fw-medium mx-0">
                    <div class="col-sm-4 col-md-3"><i class="bi bi-calendar3 me-1.5 text-secondary"></i><strong>Hari:</strong> {{ $infoKelas->hari ?? '-' }}</div>
                    <div class="col-sm-4 col-md-3"><i class="bi bi-journal-check me-1.5 text-secondary"></i><strong>Beban SKS:</strong> {{ $infoKelas->sks ?? '0' }}</div>
                    <div class="col-sm-4 col-md-4"><i class="bi bi-people-fill me-1.5 text-secondary"></i><strong>Total Mahasiswa:</strong> <span class="text-dark fw-bold">{{ $daftarHadir->count() }} Orang</span></div>
                </div>

                <div class="table-responsive rounded-3 border overflow-hidden shadow-sm">
                    <table class="table table-hover table-rekap-tech m-0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 120px;">Waktu Scan</th>
                                <th>Nama Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Hari</th> 
                                <th class="text-center">SKS</th>  
                                <th class="text-center" style="width: 130px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarHadir as $mhs)
                            <tr>
                                <td class="text-center fw-bold" style="color: #0f2b5c;">
                                    {{ \Carbon\Carbon::parse($mhs->scan_at)->format('H:i') }} <span class="small font-monospace">WIB</span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $mhs->user->name ?? 'N/A' }}</td>
                                <td>{{ $infoKelas->nama_mk ?? '-' }}</td>
                                <td class="text-center"><span class="badge bg-light text-dark border px-2 py-1">{{ $infoKelas->hari ?? '-' }}</span></td>
                                <td class="text-center fw-bold">{{ $infoKelas->sks ?? '0' }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $mhs->status == 'hadir' ? 'badge-status-hadir' : 'badge-status-lainnya' }}">
                                        {{ ucfirst($mhs->status ?? 'alfa') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @empty
        <div class="alert alert-info text-center py-5 border-0 shadow-sm rounded-4" style="background-color: #f0fdfa; color: #0f766e; border-left: 5px solid #0f766e !important;">
            <div class="mb-2">
                <i class="bi bi-cloud-slash text-muted" style="font-size: 3.5rem;"></i>
            </div>
            <h6 class="fw-bold mb-1">Belum Ada Aktivitas Terdaftar</h6>
            <p class="small mb-0 opacity-75">Sistem belum mendeteksi adanya catatan absensi log mahasiswa pada basis data.</p>
        </div>
    @endforelse
</div>
@endsection