@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4"><i class="bi bi-person-gear me-2"></i>Pengaturan Akun & Profil</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold">Data Pribadi</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Role Sistem</label>
                            <input type="text" class="form-control bg-light" value="{{ strtoupper(Auth::user()->role) }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Perbarui Profil</button>
                    </form>
                </div>
            </div>
         
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Keamanan Akun</h5>
                            <p class="small text-white-50 mb-0">Pastikan password Anda kuat untuk menjaga panel tetap aman.</p>
                        </div>
                        <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalPassword">
                            Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0 text-center p-4">
                <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                <span class="badge rounded-pill bg-success px-3">Administrator Aktif</span>
            </div>

            <div class="card shadow-sm p-4 bg-light border-0">
                <h6 class="fw-bold small mb-3 text-uppercase">Informasi Sistem</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Terakhir Login:</span>
                    <span class="small fw-bold">{{ Auth::user()->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="small text-muted">Versi Web:</span>
                    <span class="small fw-bold">v1.2.0-stable</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-dark" id="modalPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection