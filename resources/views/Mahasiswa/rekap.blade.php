@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="h3 mb-4 text-gray-800">Riwayat Absensi Saya</h3>

    {{-- 1. Kita kelompokkan data absensi berdasarkan kelas_id agar rapi per Mata Kuliah --}}
    @forelse($rekap->groupBy('sesi.kelas_id') as $kelasId => $daftarAbsen)
        @php 
            // Mengambil info kelas dari data pertama dalam grup ini
            $infoKelas = $daftarAbsen->first()->sesi->kelas; 
        @endphp

        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ $infoKelas->mataKuliah->nama_mk }} ({{ $infoKelas->kode_kelas }})
                </h6>
                
                {{-- 2. Tombol Download Sekarang Berada di dalam Loop, jadi variabel $kelasId tersedia --}}
                <a href="{{ route('mahasiswa.rekap.export', ['id' => $kelasId]) }}" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel fa-sm text-white-50"></i> Download Rekap Semester
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Hari:</strong> {{ $infoKelas->hari }}</div>
                    <div class="col-md-4"><strong>SKS:</strong> {{ $infoKelas->mataKuliah->sks }}</div>
                    <div class="col-md-4"><strong>Total Kehadiran:</strong> {{ $daftarAbsen->count() }} Pertemuan</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam Scan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daftarAbsen as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->scan_at)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->scan_at)->format('H:i') }} WIB</td>
                                <td>
                                    @if($item->status == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        {{-- Jika tidak ada data sama sekali --}}
        <div class="alert alert-info text-center">
            Belum ada riwayat absensi yang tercatat untuk Anda.
        </div>
    @endforelse
</div>
@endsection