@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h4 class="mb-4">Selamat Datang, {{ Auth::user()->name }}</h4>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div id="reader" style="width: 100%;"></div>
                    <p id="status" class="mt-3 text-primary">Siap melakukan scan...</p>
                </div>
            </div>

            <form id="absensi-form" action="{{ route('mahasiswa.absensi.scan') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="qr_token" id="qr_token">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </form>
        </div>
    </div>
</div>

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
                Swal.fire('Error', 'Harap aktifkan GPS Anda untuk melakukan absensi!', 'error');
            });
        }
    }

    // Jalankan GPS saat halaman dimuat
    getLocation();

    // 2. Inisialisasi Scanner
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scanner jika sukses
        html5QrcodeScanner.clear();
        
        document.getElementById('qr_token').value = decodedText;
        document.getElementById('status').innerText = "Memproses absensi...";

        // Ambil data dari form
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
                Swal.fire('Berhasil!', data.message, 'success')
                    .then(() => {
                        // Pastikan rute riwayat ini juga ada di web.php Anda
                        window.location.href = "{{ route('mahasiswa.dashboard') }}";
                    });
            } else {
                Swal.fire('Gagal', data.message, 'error').then(() => location.reload());
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem saat mengirim data.', 'error');
        });
    }

    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection