@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Aktifkan Sesi Absensi</h5>
                </div>
                <div class="card-body">
                    @if(isset($kelas))
                        <div class="alert alert-info">
                            <h6>INFORMASI KELAS</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- Mengambil nama_mk dari data kelas yang dikirim controller --}}
                                    <p><strong>Mata Kuliah:</strong> {{ $kelas->nama_mk }}</p>
                                    <p><strong>Kode Kelas:</strong> {{ $kelas->kode_kelas }}</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('dosen.sesi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                            {{-- Perbaikan: ID harus sesuai dengan yang dipanggil di script --}}
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <div class="mb-3">
                                <label class="form-label">Durasi QR (Menit)</label>
                                <div class="input-group">
                                    <input type="number" name="durasi" class="form-control" value="15" required>
                                    <span class="input-group-text">Menit</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Radius Absensi (Meter)</label>
                                <div class="input-group">
                                    <input type="number" name="radius" class="form-control" value="50" required>
                                    <span class="input-group-text">Meter</span>
                                </div>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> Jarak maksimal mahasiswa dari posisi Anda.</small>
                            </div>

                            {{-- Elemen Status Lokasi --}}
                            <div id="locationStatus" class="mb-3 small text-warning">
                                <i class="fas fa-spinner fa-spin"></i> Sedang mengambil lokasi GPS...
                            </div>

                            <button type="submit" id="btnSubmit" class="btn btn-primary w-100" disabled>
                                <i class="bi bi-play-circle"></i> Buka Sesi & Tampilkan QR
                            </button>
                            
                            <a href="{{ route('dosen.dashboard') }}" class="btn btn-light w-100 mt-2">Batal</a>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            Data kelas tidak ditemukan. Silakan kembali ke dashboard.
                        </div>
                        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary w-100">Kembali</a>
                    @endif 
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GPS OTOMATIS --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const statusText = document.getElementById('locationStatus');
        const submitBtn = document.getElementById('btnSubmit');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Berhasil ambil lokasi
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                    
                    statusText.innerHTML = '<i class="fas fa-check-circle text-success"></i> Lokasi berhasil dikunci.';
                    submitBtn.disabled = false; // Tombol aktif setelah GPS terkunci
                },
                function(error) {
                    // Gagal ambil lokasi karena izin ditolak atau GPS mati
                    statusText.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Akses lokasi ditolak. Harap izinkan browser mengakses GPS.';
                    alert("Gagal mengambil lokasi: " + error.message);
                },
                { enableHighAccuracy: true }
            );
        } else {
            statusText.innerHTML = "Browser Anda tidak mendukung fitur lokasi.";
        }
    });
</script>
@endsection