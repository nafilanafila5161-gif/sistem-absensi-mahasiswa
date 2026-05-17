@extends('layouts.admin')

@section('content')
<div class="text-center mt-5">
    <div class="card d-inline-block p-4 shadow">
        <h3>Silakan Scan untuk Absensi</h3>
        <p class="text-muted">{{ $sesi->kelas->mataKuliah->nama_mk }} - {{ $sesi->kelas->kode_kelas }}</p>
        
        <!-- Kita gunakan library SimpleQRCode -->
        <div class="p-3 bg-white border">
            {!! QrCode::size(300)->generate($sesi->token_qr) !!}
        </div>
        
        <p class="mt-3"><strong>Token:</strong> {{ $sesi->token_qr }}</p>
        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary mt-2">Selesai / Kembali</a>
    </div>
</div>
@endsection