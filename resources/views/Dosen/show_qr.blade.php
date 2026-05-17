@extends('layouts.admin')

@section('content')
<div class="text-center mt-5">
    <div class="card d-inline-block p-4 shadow">
        <h3>Silakan Scan untuk Absensi</h3>
        
        {{-- Perbaikan: Menggunakan null coalescing ?? agar tidak error jika data kelas kosong --}}
        <p class="text-muted">
            {{ $sesi->kelas->nama_mk ?? 'Mata Kuliah Tidak Diketahui' }} - {{ $sesi->kelas->kode_kelas ?? '-' }}
        </p>
        
        <div class="p-3 bg-white border">
            {{-- Pastikan library QrCode sudah terinstal --}}
            {!! QrCode::size(300)->generate($sesi->qr_token) !!}
        </div>
        
        <p class="mt-3"><strong>Token:</strong> {{ $sesi->qr_token }}</p>
        
        {{-- Tombol kembali ke dashboard --}}
        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary mt-2">Selesai / Kembali</a>
    </div>
    {{-- Tambahkan ini di bawah QR Code --}}
<div class="mt-3">
    @if(\Carbon\Carbon::now()->greaterThan($sesi->waktu_selesai))
        <span class="badge bg-danger p-2">SESI TELAH BERAKHIR</span>
    @else
        <span class="badge bg-success p-2">SESI AKTIF</span>
    @endif
</div>
</div>
@endsection