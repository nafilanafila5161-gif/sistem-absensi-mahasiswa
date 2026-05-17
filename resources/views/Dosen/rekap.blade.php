@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="h3 mb-4 text-gray-800">Manajemen Rekap Absensi</h3>

    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary text-uppercase">Download Rekap Per Semester</h6>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Mengambil kelas-kelas unik agar dosen bisa memilih kelas & angkatan tertentu --}}
                @foreach($rekap->unique('sesi.kelas_id') as $data)
                    @php $kelas = $data->sesi->kelas; @endphp
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light shadow-sm">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="small text-gray-500">{{ $kelas->kode_kelas }}</div>
                                    <div class="font-weight-bold text-dark">{{ $kelas->mataKuliah->nama_mk }}</div>
                                    {{-- Menampilkan Hari dan SKS di identitas kelas --}}
                                    <div class="small mt-1">
                                        <span class="badge badge-secondary">{{ $kelas->hari }}</span>
                                        <span class="badge badge-info">{{ $kelas->mataKuliah->sks }} SKS</span>
                                    </div>
                                </div>
                                <a href="{{ route('dosen.rekap.export_semester', ['id' => $kelas->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr>

    <h5 class="font-weight-bold text-gray-800 mb-3">Daftar Kehadiran Per Pertemuan</h5>
    @forelse($rekap->groupBy('sesi_id') as $sesiId => $daftarHadir)
        @php 
            $sesi = $daftarHadir->first()->sesi;
            $infoKelas = $sesi->kelas;
        @endphp
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gray-100">
                <span>
                    <strong>{{ $infoKelas->mataKuliah->nama_mk }} ({{ $infoKelas->kode_kelas }})</strong> | 
                    Pertemuan: {{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('d M Y') }}
                </span>
                <a href="{{ route('dosen.rekap.export_pertemuan', ['id' => $sesiId]) }}" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel"></i> Rekap Hari Ini
                </a>
            </div>
            <div class="card-body">
                {{-- Info Tambahan untuk memperjelas tabel --}}
                <div class="row mb-3 small text-muted">
                    <div class="col-md-3"><strong>Hari:</strong> {{ $infoKelas->hari }}</div>
                    <div class="col-md-3"><strong>SKS:</strong> {{ $infoKelas->mataKuliah->sks }}</div>
                    <div class="col-md-3"><strong>Total Mahasiswa:</strong> {{ $daftarHadir->count() }}</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm" width="100%">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Waktu Scan</th>
                                <th>Nama Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th>Hari</th> <th>SKS</th>  <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarHadir as $mhs)
                            <tr>
                                <td class="text-center">{{ \Carbon\Carbon::parse($mhs->scan_at)->format('H:i') }} WIB</td>
                                <td>{{ $mhs->user->name ?? 'N/A' }}</td>
                                <td>{{ $infoKelas->mataKuliah->nama_mk }}</td>
                                <td class="text-center">{{ $infoKelas->hari }}</td>
                                <td class="text-center">{{ $infoKelas->mataKuliah->sks }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $mhs->status == 'hadir' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($mhs->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-4">
            Belum ada aktivitas absensi yang tercatat.
        </div>
    @endforelse
</div>
@endsection