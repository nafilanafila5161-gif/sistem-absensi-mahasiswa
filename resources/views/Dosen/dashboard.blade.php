@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Dosen</h2>
        <a href="{{ route('dosen.kelas.tambah') }}" class="btn btn-primary">+ Tambah Kelas Baru</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6>Total Kelas</h6>
                    <h3>{{ count($daftar_kelas ?? []) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white"><strong>Daftar Kelas Anda</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
        <td>{{ $item->nama_mk }}</td>
        <td>{{ $item->kode_kelas }}</td>
        <td>{{ $item->hari }}</td>
        <td>{{ $item->sks ?? '-' }} SKS</td>
        <td class="text-center">
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('dosen.buka_absen', $item->id) }}" class="btn btn-sm btn-info text-white">Buka Absensi</a>
                <a href="{{ route('dosen.kelas.edit', $item->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                
                <form id="delete-form-{{ $item->id }}" action="{{ route('dosen.hapus_kelas', $item->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">Hapus</button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center">
            Data tidak ditemukan. User ID Anda saat ini: {{ auth()->id() }}
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
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection