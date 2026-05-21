@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Kelas Baru
                    </h5>
                    <p class="text-muted small mb-0">Input parameter kelas sesuai dengan mata kuliah yang Anda ampu.</p>
                </div>
                
                <div class="card-body px-4 pb-4 pt-2">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosen.kelas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" class="form-control" placeholder="Contoh: Pemrograman Web" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" placeholder="Contoh: IF-A 2024" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">Hari Kuliah</label>
                                <select name="hari" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">Jumlah SKS</label>
                                <input type="number" name="sks" class="form-control" placeholder="Contoh: 3" min="1" max="6" required>
                            </div>
                        </div>
                        <div class="row">
    <div class="form-group mb-3">
    <label>Jam Mulai Kuliah</label>
    <input type="time" name="jam_mulai" class="form-control" required>
</div>

<div class="form-group mb-3">
    <label>Jam Selesai Kuliah</label>
    <input type="time" name="jam_selesai" class="form-control" required>
</div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Kelas</button>
                            <a href="{{ route('dosen.dashboard') }}" class="btn btn-light px-4 border">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection