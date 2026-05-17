@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Tambah Kelas Baru</div>
                <div class="card-body">
                    <form action="{{ route('dosen.kelas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select name="mata_kuliah_id" class="form-control" required>
                                @foreach($mata_kuliah as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }} ({{ $mk->kode_mk }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Kode Kelas (Contoh: TI-A)</label>
                            <input type="text" name="kode_kelas" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Latitude</label>
                                <input type="text" name="latitude" id="lat" class="form-control" readonly required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Longitude</label>
                                <input type="text" name="longitude" id="lng" class="form-control" readonly required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Radius Absensi (Meter)</label>
                            <input type="number" name="radius_meter" id="radius" class="form-control" value="50" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Kelas</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Pilih Lokasi Kelas (Klik pada Peta)</div>
                <div class="card-body">
                    <!-- Element untuk Peta -->
                    <div id="map" style="height: 450px; border-radius: 10px;"></div>
                    <p class="text-muted mt-2"><small>*Klik pada peta untuk menentukan titik koordinat gedung/ruangan kelas.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('content')
<div class="card shadow">
    <div class="card-header bg-white"><h4>Setup Lokasi & Jadwal Kelas</h4></div>
    <div class="card-body">
        <form action="{{ route('dosen.kelas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Pilih Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-control">
                        @foreach($mata_kuliah as $mk)
                            <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Latitude</label>
                    <input type="text" id="lat" name="latitude" class="form-control" placeholder="-6.12345" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Longitude</label>
                    <input type="text" id="lng" name="longitude" class="form-control" placeholder="106.12345" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Parameter Kelas</button>
        </form>
        
        <p class="mt-3 text-muted"><small>*Tips: Anda bisa menggunakan Google Maps untuk mendapatkan titik koordinat ruangan kelas.</small></p>
    </div>
</div>
@endsection
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // 1. Inisialisasi Peta (Default ke lokasi umum atau kampus)
    var map = L.map('map').setView([-6.200000, 106.816666], 15); 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker;
    var circle;

    // 2. Logika Klik Peta untuk Ambil Koordinat[cite: 2]
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        var rad = document.getElementById('radius').value;

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        // Hapus marker & circle lama jika ada
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);

        // Tambah marker baru di lokasi klik
        marker = L.marker([lat, lng]).addTo(map);
        
        // Tambah circle untuk visualisasi radius geofencing[cite: 2]
        circle = L.circle([lat, lng], {
            color: 'blue',
            fillColor: '#30f',
            fillOpacity: 0.2,
            radius: rad
        }).addTo(map);
    });

    // Update circle jika radius diubah manual
    document.getElementById('radius').addEventListener('input', function() {
        if (circle) circle.setRadius(this.value);
    });
</script>
@endsection