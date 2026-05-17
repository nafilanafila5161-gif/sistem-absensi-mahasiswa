@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Kelas Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dosen.kelas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="fw-bold">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" class="form-control" placeholder="Contoh: Pemrograman Java" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" placeholder="Contoh: IF-4A" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Hari</label>
                            <select name="hari" class="form-control" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">SKS</label>
                            <input type="number" name="sks" class="form-control" placeholder="Contoh: 3" min="1" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Kelas
                            </button>
                            <a href="{{ route('dosen.dashboard') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form> {{-- Tag penutup form sekarang berada di tempat yang benar --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection