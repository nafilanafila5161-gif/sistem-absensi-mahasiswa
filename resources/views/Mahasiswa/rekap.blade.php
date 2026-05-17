
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Absensi Saya</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->created_at->format('d M Y, H:i') }} WIB</td>
                                    <td>{{ $data->kelas->nama_matkul ?? 'Tidak Diketahui' }}</td>
                                    <td>{{ $data->kelas->nama_kelas ?? '-' }}</td>
                                    <td>
                                        @if($data->status == 'hadir')
                                            <span class="badge bg-success">Hadir</span>
                                        @elseif($data->status == 'terlambat')
                                            <span class="badge bg-warning text-dark">Terlambat</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($data->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data absensi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection