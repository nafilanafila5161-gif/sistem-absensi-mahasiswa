@extends('layouts.admin')

@section('content')
<!-- Tambahan Style untuk Menyelaraskan Tema Biru Navy Teknik -->
<style>
    /* Sinkronisasi warna utama bertema Navy Teknik Bergradasi */
    .btn-slate-dark {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        transition: all 0.25s ease-in-out;
    }
    .btn-slate-dark:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        box-shadow: 0 4px 15px rgba(15, 43, 92, 0.25);
        color: #ffffff !important;
    }
    
    /* Mempercantik tampilan Card Utama dengan aksen border tipis */
    .card-custom-user {
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        background-color: #ffffff;
        overflow: hidden;
    }

    /* Kustomisasi Nav Pills (Tab Dosen & Mahasiswa) khas Engineering */
    .nav-pills .nav-link {
        color: #64748b !important;
        border-radius: 8px !important;
        padding: 8px 16px;
        transition: all 0.2s ease;
    }
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(15, 43, 92, 0.2) !important;
    }

    /* Memperhalus tampilan tabel agar seimbang */
    .table thead th {
        background-color: #f8fafc !important;
        color: #0f2b5c !important; /* Diubah ke Navy Utama */
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 14px 16px;
    }
    .table tbody td {
        padding: 14px 16px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(15, 43, 92, 0.02) !important; /* Sorotan hover warna biru tipis */
    }

    /* Kustomisasi input di dalam modal */
    .form-control-custom:focus, .form-select-custom:focus {
        border-color: #0f2b5c !important;
        box-shadow: 0 0 0 0.22rem rgba(15, 43, 92, 0.15) !important;
    }

    /* Alert info kustom di dalam modal (Kombinasi hijau emerald soft) */
    .alert-info-custom {
        background-color: #f0fdf4 !important;
        border: 1px solid #bbf7d0 !important;
        color: #166534 !important;
        border-radius: 10px;
    }
    
    /* Judul Utama */
    .section-title {
        color: #0a192f;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0 section-title">Pengelolaan Data User</h3>
    <button type="button" class="btn btn-slate-dark shadow-sm px-3 py-2 fw-medium" data-bs-toggle="modal" data-bs-target="#modalTambahUser" style="border-radius: 10px;">
        <i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru
    </button>
</div>

{{-- Notifikasi Sukses via SweetAlert --}}
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

<div class="card card-custom-user">
    <div class="card-header bg-white p-3 border-0">
        <ul class="nav nav-pills card-header-pills" id="userTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="dosen-tab" data-bs-toggle="tab" data-bs-target="#dosen" type="button" role="tab">
                    <i class="bi bi-person-badge me-2"></i>Dosen
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button" role="tab">
                    <i class="bi bi-people me-2"></i>Mahasiswa
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="userTabContent">
            
            {{-- TAB DOSEN --}}
            <div class="tab-pane fade show active" id="dosen" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users->where('role', 'dosen') as $user)
                            <tr>
                                <td class="fw-medium" style="color: #0a192f;">{{ $user->name }}</td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td class="text-center">
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm text-danger border-0 bg-transparent" onclick="confirmDelete('{{ $user->id }}', 'Dosen')" title="Hapus User">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-5">Belum ada data Dosen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB MAHASISWA --}}
            <div class="tab-pane fade" id="mahasiswa" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users->where('role', 'mahasiswa') as $user)
                            <tr>
                                <td class="fw-medium" style="color: #0a192f;">{{ $user->name }}</td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td class="text-center">
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm text-danger border-0 bg-transparent" onclick="confirmDelete('{{ $user->id }}', 'Mahasiswa')" title="Hapus User">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-5">Belum ada data Mahasiswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH USER --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog shadow-lg modal-dialog-centered">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <!-- Kepala modal menggunakan identitas Navy Teknik Utama -->
                <div class="modal-header text-white p-3 border-0" style="background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%);">
                    <h5 class="modal-title fw-bold fs-6"><i class="bi bi-person-plus me-2"></i>Daftarkan User Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control form-control-custom py-2" required placeholder="Masukkan nama lengkap...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control form-control-custom py-2" required placeholder="email@domain.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Role / Peran</label>
                        <select name="role" class="form-select form-select-custom py-2" required>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                        </select>
                    </div>
                    <div class="alert alert-info-custom py-2 small mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>Password acak akan dikirim ke email user via Mailtrap.
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-slate-dark px-4 py-2 fw-medium" style="border-radius: 8px;">Daftarkan & Kirim Email</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT SWEETALERT UNTUK DELETE --}}
<script>
function confirmDelete(id, roleLabel) {
    Swal.fire({
        title: 'Hapus ' + roleLabel + '?',
        text: "Data ini akan dihapus secara permanen dan tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0f2b5c', /* Tombol OK dirubah ke Navy Teknik */
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection