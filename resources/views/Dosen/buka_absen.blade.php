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
                                    <p><strong>Mata Kuliah:</strong> {{ $kelas->nama_mk }}</p>
                                    <p><strong>Kode Kelas:</strong> {{ $kelas->kode_kelas }}</p>
                                </div>
                            </div>
                        </div>

                        <form id="formBukaSesi">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <div class="mb-3">
                                <label class="form-label">Durasi QR (Menit)</label>
                                <div class="input-group">
                                    <input type="number" name="durasi" class="form-control" value="15" required min="1">
                                    <span class="input-group-text">Menit</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Radius Absensi (Meter)</label>
                                <div class="input-group">
                                    <input type="number" name="radius" class="form-control" value="50" required min="5">
                                    <span class="input-group-text">Meter</span>
                                </div>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> Jarak maksimal mahasiswa dari posisi Anda.</small>
                            </div>

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

<div class="modal fade" id="qrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-body text-center p-5 pb-3">
                <h4 class="fw-bold mb-1" id="qrModalLabel">Silakan Scan untuk Absensi</h4>
                <p class="text-muted small id-info-kelas"></p>
                
                <div class="my-4 d-flex justify-content-center">
                    <div id="qrcodeCanvas" style="padding: 10px; background: white; border: 1px solid #ddd; border-radius: 4px;"></div>
                </div>

                <div class="alert alert-light border small text-break text-start mb-4">
                    <strong>Token Aktif:</strong> <br>
                    <span id="tokenText" class="text-muted font-monospace small"></span>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0 d-flex flex-column gap-2">
                
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-success w-100 py-3 fw-bold text-uppercase shadow" style="position: relative; z-index: 9999 !important; font-size: 0.95rem; text-decoration: none;">
                    <i class="fas fa-check-circle"></i> Sesi Berhasil Dibuka (Klik Selesai)
                </a>

                <a href="{{ route('dosen.dashboard') }}" class="btn btn-light text-muted w-100 py-2 btn-sm border-0" style="text-decoration: none; font-size: 0.85rem;">
                    Selesai & Kembali ke Dashboard
                </a>
                
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const statusText = document.getElementById('locationStatus');
        const submitBtn = document.getElementById('btnSubmit');
        const formSesi = document.getElementById('formBukaSesi');

        // 1. Geolocation GPS
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                    
                    statusText.className = "mb-3 small text-success";
                    statusText.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi berhasil dikunci.';
                    submitBtn.disabled = false; 
                },
                function(error) {
                    statusText.className = "mb-3 small text-danger";
                    statusText.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Akses lokasi ditolak. Harap izinkan browser mengakses GPS.';
                },
                { enableHighAccuracy: true }
            );
        } else {
            statusText.className = "mb-3 small text-danger";
            statusText.innerHTML = "Browser Anda tidak mendukung fitur lokasi.";
        }

        // 2. AJAX Post Form
        if (formSesi) {
            formSesi.addEventListener('submit', function(e) {
                e.preventDefault(); 

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Sesi...';

                let formData = new FormData(this);

                fetch("{{ route('dosen.sesi.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memproses data ke server.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('qrcodeCanvas').innerHTML = "";

                        // Render QR Code ke modal secara instan
                        new QRCode(document.getElementById("qrcodeCanvas"), {
                            text: data.qr_token,
                            width: 256,
                            height: 256,
                            correctLevel: QRCode.CorrectLevel.H
                        });

                        document.querySelector('.id-info-kelas').innerText = data.nama_mk + " (" + data.kode_mk + ")";
                        document.getElementById('tokenText').innerText = data.qr_token;

                        // Panggil Modal Pop-up
                        let qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                        qrModal.show();
                    } else {
                        alert('Gagal membuka sesi absensi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-play-circle"></i> Buka Sesi & Tampilkan QR';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi sistem.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-play-circle"></i> Buka Sesi & Tampilkan QR';
                });
            });
        }
    });
</script>
@endsection