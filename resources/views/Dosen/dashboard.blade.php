@extends('layouts.admin')

@section('content')
<!-- Tambahan Style Premium - Dashboard Dosen Cyber Navy Engineering -->
<style>
    /* Efek blueprint mesh grid pada latar belakang halaman dashboard */
    .container-custom-dashboard {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Kartu Utama dengan Glow Top-Bar */
    .card-tech-panel {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        background: #ffffff;
        overflow: hidden;
    }
    .card-tech-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
    }

    /* Modul Taktis Total Kelas */
    .card-metric-tech {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 25px rgba(10, 25, 47, 0.12) !important;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease;
    }
    .card-metric-tech:hover {
        transform: translateY(-2px);
    }
    .card-metric-tech::after {
        content: '';
        position: absolute;
        right: -20px;
        bottom: -20px;
        width: 100px;
        height: 100px;
        background: rgba(0, 242, 254, 0.04);
        border-radius: 50%;
    }

    /* Desain Tabel Premium */
    .table-tech {
        vertical-align: middle !important;
    }
    .table-tech thead th {
        background: #0a192f !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px !important;
        border: none !important;
    }
    .table-tech tbody tr {
        transition: background-color 0.2s ease;
    }
    .table-tech tbody tr:hover {
        background-color: rgba(23, 70, 148, 0.03) !important;
    }
    .table-tech tbody td {
        padding: 14px 16px !important;
        color: #334155 !important;
        font-weight: 500;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Tombol Aksi Taktis */
    .btn-tech-navy {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-tech-navy:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        box-shadow: 0 6px 18px rgba(15, 43, 92, 0.23);
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    /* Tombol Mini Spesifik */
    .btn-action-absen {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600;
    }
    .btn-action-absen:hover {
        background-color: #0369a1 !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
    }
    .btn-action-edit {
        background-color: #ea580c !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600;
    }
    .btn-action-edit:hover {
        background-color: #c2410c !important;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.2);
    }
    .btn-action-hapus {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600;
    }
    .btn-action-hapus:hover {
        background-color: #b91c1c !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    /* Badges & Kode Kelas Minimalis */
    .code-badge {
        background-color: #f1f5f9;
        color: #0f2b5c;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
</style>

<div class="container-fluid container-custom-profile py-4">
    <!-- Header Dashboard -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold m-0" style="color: #0a192f;">
                <i class="bi bi-grid-1x2-fill me-2" style="color: #0f2b5c;"></i>Dashboard Dosen
            </h3>
            <p class="text-muted small mb-0">Selamat datang kembali. Kelola jadwal perkuliahan dan absensi kelas Anda di sini.</p>
        </div>
        <a href="{{ route('dosen.kelas.tambah') }}" class="btn btn-tech-navy d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah Kelas Baru
        </a>
    </div>

    <!-- Sektor Ringkasan Metrik -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="card card-metric-tech p-3 text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-white-50 fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Kelas</p>
                        <h2 class="fw-bold mb-0">{{ count($daftar_kelas ?? []) }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-journal-bookmark-fill text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Tabel Utama -->
    <div class="card card-tech-panel">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
            <h5 class="fw-bold m-0" style="color: #0a192f;">
                <i class="bi bi-collection me-2" style="color: #0f2b5c;"></i>Daftar Kelas Anda
            </h5>
        </div>
        <div class="card-body px-4 pb-4 pt-2">
            <div class="table-responsive rounded-3 overflow-hidden border border-light shadow-sm">
                <table class="table table-hover table-tech m-0">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Kode Kelas</th>
                            <th>Hari</th>
                            <th>SKS</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_kelas as $item)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $item->nama_mk }}</td>
                            <td><span class="code-badge">{{ $item->kode_kelas }}</span></td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3 text-muted small"></i> {{ $item->hari }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border border-secondary border-opacity-10 px-2.5 py-1.5 fw-semibold">
                                    {{ $item->sks ?? '-' }} SKS
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('dosen.buka_absen', $item->id) }}" class="btn btn-sm btn-action-absen d-inline-flex align-items-center gap-1 px-3 py-1.5 rounded-3 shadow-sm">
                                        <i class="bi bi-qr-code"></i> Buka Absensi
                                    </a>
                                    <a href="{{ route('dosen.kelas.edit', $item->id) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1 px-2.5 py-1.5 rounded-3 shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('dosen.hapus_kelas', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-action-hapus d-inline-flex align-items-center gap-1 px-2.5 py-1.5 rounded-3 shadow-sm" onclick="confirmDelete('{{ $item->id }}')">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <div class="mb-2">
                                    <i class="bi bi-folder-x text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <p class="mb-1 fw-semibold">Data kelas tidak ditemukan.</p>
                                <span class="badge bg-light text-muted border px-3 py-2 small">ID Log Anda: {{ auth()->id() }}</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus kelas ini?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        background: '#ffffff',
        customClass: {
            title: 'fw-bold text-dark fs-5 pt-3',
            htmlContainer: 'text-secondary small',
            confirmButton: 'btn btn-danger px-4 py-2 rounded-3 fw-semibold',
            cancelButton: 'btn btn-secondary px-4 py-2 rounded-3 fw-semibold'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection