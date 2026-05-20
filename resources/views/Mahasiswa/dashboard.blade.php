@extends('layouts.admin')

@section('content')
<!-- Tambahan Style Premium - Scanner QR Mahasiswa Cyber Navy Engineering -->
<style>
    /* Efek blueprint mesh grid pada latar belakang halaman scanner */
    .container-custom-scanner {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
        min-height: 80vh;
    }

    /* Kartu Utama Panel Scanner */
    .card-tech-scanner {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.1) !important;
        border-radius: 20px !important;
        box-shadow: 0 15px 35px rgba(10, 25, 47, 0.05) !important;
        background: #ffffff;
        overflow: hidden;
    }
    
    /* Aksen Top Bar Khas Cyber Navy */
    .card-tech-scanner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
        z-index: 2;
    }

    /* Kustomisasi Tampilan Pustaka Html5-Qrcode */
    #reader {
        border: none !important;
        border-radius: 12px;
        overflow: hidden;
        background: #fafafa;
    }
    #reader video {
        border-radius: 12px;
        object-fit: cover;
    }
    
    /* Mempercantik tombol bawaan dari html5-qrcode */
    #reader button {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        padding: 8px 18px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 10px 0 !important;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(10, 25, 47, 0.15);
    }
    #reader button:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        transform: translateY(-1px);
    }

    /* Desain Bingkai Pemindai Kamera Cyber */
    .scanner-viewport-wrapper {
        position: relative;
        padding: 6px;
        border: 2px dashed rgba(23, 70, 148, 0.2);
        border-radius: 16px;
        background-color: #ffffff;
    }

    /* Lencana Status Dinamis */
    .status-badge-pulse {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: rgba(2, 132, 199, 0.06);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 8px 20px;
        border-radius: 50px;
        border: 1px solid rgba(2, 132, 199, 0.15);
    }

    /* Animasi denyut kecil pada ikon indikator radar */
    .radar-dot {
        width: 8px;
        height: 8px;
        background-color: #0284c7;
        border-radius: 50%;
        position: relative;
    }
    .radar-dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: #0284c7;
        border-radius: 50%;
        animation: pulse-radar 1.5s infinite ease-in-out;
    }

    @keyframes pulse-radar {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(3); opacity: 0; }
    }
</style>

<div class="container container-custom-scanner py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 text-center">
            
            <!-- Berita Selamat Datang / Identitas Mahasiswa -->
            <div class="mb-4">
                <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill uppercase mb-2 fw-semibold tracking-wider" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-check text-primary me-1"></i> Autentikasi Mahasiswa Berhasil
                </span>
                <h4 class="fw-bold" style="color: #0a192f;">
                    Selamat Datang, <span class="text-decoration-underline" style="color: #174694;">{{ Auth::user()->name }}</span>
                </h4>
                <p class="text-muted small">Silakan arahkan kamera perangkat Anda tepat ke arah Kode QR perkuliahan yang ditampilkan oleh dosen.</p>
            </div>
            
            <!-- Kotak Utama Kamera Pemindai -->
            <div class="card card-tech-scanner p-3 mb-4">
                <div class="card-body p-2 p-sm-3">
                    <div class="scanner-viewport-wrapper shadow-sm">
                        <!-- Wadah Engine Scanner HTML5-QRCODE -->
                        <div id="reader" style="width: 100%;"></div>
                    </div>
                    
                    <!-- Area Indikator Status Operasional -->
                    <div class="mt-4 mb-2">
                        <div id="status-container">
                            <span class="status-badge-pulse">
                                <span class="radar-dot" id="status-dot"></span>
                                <span id="status">Siap melakukan scan...</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Enkapsulasi Tersembunyi (Parameter Pengiriman Data) -->
            <form id="absensi-form" action="{{ route('mahasiswa.absensi.scan') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="qr_token" id="qr_token">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </form>
            
        </div>
    </div>
</div>

<!-- Skrip Pustaka Eksternal -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Dapatkan Lokasi GPS Mahasiswa secara Real-time
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, function(error) {
                Swal.fire({
                    title: 'Akses GPS Diperlukan',
                    text: 'Harap aktifkan fitur lokasi/GPS pada perangkat Anda untuk melakukan validasi presensi kehadiran kelas!',
                    icon: 'error',
                    confirmButtonText: 'Saya Mengerti',
                    confirmButtonColor: '#0f2b5c',
                    customClass: {
                        title: 'fw-bold text-dark fs-5',
                        confirmButton: 'btn btn-primary px-4 py-2 rounded-3 fw-semibold'
                    },
                    buttonsStyling: false
                });
            });
        }
    }

    // Jalankan GPS saat halaman pertama kali dimuat
    getLocation();

    // 2. Inisialisasi Engine Scanner
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scanner jika pemindaian sukses dilakukan
        html5QrcodeScanner.clear();
        
        document.getElementById('qr_token').value = decodedText;
        
        // Modifikasi kosmetik status saat loading memproses
        let statusText = document.getElementById('status');
        let statusDot = document.getElementById('status-dot');
        statusText.innerText = "Memproses absensi...";
        statusText.parentElement.style.color = "#ea580c";
        statusText.parentElement.style.backgroundColor = "rgba(234, 88, 12, 0.06)";
        statusText.parentElement.style.borderColor = "rgba(234, 88, 12, 0.15)";
        statusDot.style.backgroundColor = "#ea580c";

        // Ambil data dari form enkapsulasi
        let form = document.getElementById('absensi-form');
        let formData = new FormData(form);

        // Kirim data menggunakan AJAX (fetch)
        fetch("{{ route('mahasiswa.absensi.scan') }}", {
            method: "POST",
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' || data.status === 'hadir') {
                Swal.fire({
                    title: 'Presensi Berhasil!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Ke Dashboard',
                    confirmButtonColor: '#059669',
                    customClass: {
                        title: 'fw-bold text-success fs-4',
                        confirmButton: 'btn btn-success px-4 py-2 rounded-3 fw-semibold'
                    },
                    buttonsStyling: false
                }).then(() => {
                    // Redirect menuju halaman riwayat / dashboard mahasiswa
                    window.location.href = "{{ route('mahasiswa.dashboard') }}";
                });
            } else {
                Swal.fire({
                    title: 'Presensi Gagal',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Ulangi Scan',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        title: 'fw-bold text-danger fs-4',
                        confirmButton: 'btn btn-danger px-4 py-2 rounded-3 fw-semibold'
                    },
                    buttonsStyling: false
                }).then(() => location.reload());
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Sistem Error',
                text: 'Terjadi kegagalan komunikasi internal saat mengirimkan data absensi.',
                icon: 'error',
                confirmButtonText: 'Muat Ulang Halaman',
                confirmButtonColor: '#64748b',
                customClass: {
                    title: 'fw-bold text-dark fs-5',
                    confirmButton: 'btn btn-secondary px-4 py-2 rounded-3 fw-semibold'
                },
                buttonsStyling: false
            });
        });
    }

    // Mengatur konfigurasi dimensi kotak pemindai kamera (Responsive Box)
    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 12, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection