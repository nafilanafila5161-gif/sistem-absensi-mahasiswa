@extends('layouts.admin')

@section('content')
<div class="text-center mt-5">
    <div class="card d-inline-block p-4 shadow">
        <h3>Silakan Scan untuk Absensi</h3>
        
        <p class="text-muted">
            {{ $sesi->kelas->nama_mk ?? 'Mata Kuliah' }} - {{ $sesi->kelas->kode_kelas ?? '-' }}
        </p>
        
        <div class="p-3 bg-white border">
            {{-- Menggunakan variabel qrcode murni dari controller --}}
            {!! $qrcode !!}
        </div>
        
        <p class="mt-3"><strong>Token:</strong> {{ $sesi->qr_token }}</p>
        
        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary mt-2">Selesai / Kembali</a>
    </div>

    {{-- Kita permudah indikator statusnya tanpa pengecekan jam laravel yang sensitif --}}
    <div class="mt-3">
        <div id="status-sesi-container">
            @if($sesi->is_active == 1)
                <button class="btn btn-sm btn-success fw-bold" disabled>
                    <i class="bi bi-check-circle me-1"></i> SESI AKTIF
                </button>
            @else
                <button class="btn btn-sm btn-danger fw-bold" disabled>
                    <i class="bi bi-exclamation-triangle me-1"></i> SESI NON-AKTIF
                </button>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isSesiActiveField = {{ $sesi->is_active }};
        const container = document.getElementById("status-sesi-container");

        // Jika status di database dinonaktifkan dosen, ubah tombol jadi merah
        if (isSesiActiveField !== 1) {
            container.innerHTML = `
                <button class="btn btn-sm btn-danger fw-bold" disabled>
                    <i class="bi bi-exclamation-triangle me-1"></i> SESI NON-AKTIF
                </button>
            `;
        }
    });
</script>
@endsection