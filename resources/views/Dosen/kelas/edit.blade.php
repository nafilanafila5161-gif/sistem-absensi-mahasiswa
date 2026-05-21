@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kelas
                    </h5>
                    <p class="text-muted small mb-0">Ubah parameter kelas sesuai kebutuhan Anda.</p>
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

                    <form action="{{ route('dosen.kelas.update', $kelas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" class="form-control" value="{{ $kelas->nama_mk }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" value="{{ $kelas->kode_kelas }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">Hari Kuliah</label>
                                <select name="hari" class="form-select" required>
                                    <option value="Senin" {{ $kelas->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ $kelas->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ $kelas->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ $kelas->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ $kelas->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ $kelas->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">Jumlah SKS</label>
                                <input type="number" name="sks" class="form-control" value="{{ $kelas->sks }}" min="1" max="6" required>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai Kuliah</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required 
                             value="{{ isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai Kuliah</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required 
                                value="{{ isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '' }}">
                        </div>
                    </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-warning px-4 fw-semibold text-white">Simpan Perubahan</button>
                            <a href="{{ route('dosen.dashboard') }}" class="btn btn-light px-4 border">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection