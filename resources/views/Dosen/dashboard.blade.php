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
                    <h3>{{ count($daftar_kelas) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white"><strong>Daftar Kelas Anda</strong></div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Kode Kelas</th>
                        <th>Angkatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftar_kelas as $kelas)
                        <tr>
                            <td>{{ $kelas->nama_mk }}</td>
                            <td>{{ $kelas->kode_kelas }}</td>
                            <td>{{ $kelas->angkatan }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada kelas yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection