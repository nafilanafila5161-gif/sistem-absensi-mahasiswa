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
                    {{-- Tampilkan informasi kelas jika $kelas tersedia --}}
                    @if(isset($kelas))
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Informasi Kelas</h6>
                            <div class="alert alert-info border-0 shadow-sm">
                                <div class="mb-1">
                                    <strong>Mata Kuliah:</strong> {{ $kelas->mataKuliah->nama_mk ?? 'Tidak Ada Data' }}
                                </div>
                                <div>
                                    <strong>Kode Kelas:</strong> {{ $kelas->kode_kelas ?? '-' }}
                                </div>
                            </div>
                        </div>      

                        <form action="{{ route('dosen.sesi.store') }}" method="POST" id="formBukaAbsen">
                            @csrf
                            {{-- Data Hidden --}}
                            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <div class="mb-3">
                                <label for="menit_aktif" class="form-label fw-bold">Durasi QR Berlaku (Menit)</label>
                                <div class="input-group">
                                    <input type="number" name="menit_aktif" id="menit_aktif" class="form-control" 
                                           placeholder="Contoh: 15" min="1" required value="15">
                                    <span class="input-group-text">Menit</span>
                                </div>
                                <div id="locationStatus" class="form-text text-warning mt-2">
                                    <i class="fas fa-map-marker-alt"></i> Sedang mengambil lokasi GPS...
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="materi" class="form-label fw-bold">Materi Perkuliahan (Opsional)</label>
                                <textarea name="materi" id="materi" class="form-control" rows="3" 
                                          placeholder="Masukkan ringkasan materi hari ini..."></textarea>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg" disabled>
                                    <i class="fas fa-play-circle"></i> Buka Sesi & Tampilkan QR
                                </button>
                                <a href="{{ route('dosen.dashboard') }}" class="btn btn-light">Batal</a>
                            </div>
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
                    submitBtn.disabled = false; // Tombol baru bisa diklik setelah GPS dapat
                },
                function(error) {
                    // Gagal ambil lokasi
                    statusText.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Gagal mengambil lokasi. Pastikan GPS aktif dan izinkan browser.';
                    alert("Error: " + error.message + ". Mohon izinkan akses lokasi agar absensi valid.");
                },
                { enableHighAccuracy: true }
            );
        } else {
            statusText.innerHTML = "Browser Anda tidak mendukung Geolocation.";
        }
    });
</script>
@endsection