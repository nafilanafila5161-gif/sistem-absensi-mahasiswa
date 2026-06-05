@extends('layouts.admin')

@section('content')
<!-- Tambahan Style Premium - Pengaturan Akun Cyber Navy Engineering -->
<style>
    /* Efek blueprint mesh grid pada latar belakang halaman */
    .container-custom-profile {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Kartu Utama dengan Glow Top-Bar */
    .card-tech-profile {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        background: #ffffff;
        overflow: hidden;
    }
    .card-tech-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0a192f, #13418a, #00f2fe);
    }

    /* Kartu Keamanan Akun Premium (Menggantikan tema dark biasa) */
    .card-security-tech {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 25px rgba(10, 25, 47, 0.15) !important;
        position: relative;
        overflow: hidden;
    }
    .card-security-tech::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 120px;
        height: 120px;
        background: rgba(0, 242, 254, 0.05);
        border-radius: 50%;
    }

    /* Avatar Samping & Utama */
    .avatar-tech-circle {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        color: #ffffff !important;
        border: 4px solid rgba(23, 70, 148, 0.1);
        box-shadow: 0 8px 20px rgba(15, 43, 92, 0.12);
        font-weight: 700;
    }

    /* Input Form Kustom */
    .form-control-tech {
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 10px 14px;
        color: #1e293b !important;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .form-control-tech:focus {
        border-color: #0f2b5c !important;
        box-shadow: 0 0 0 0.22rem rgba(15, 43, 92, 0.15) !important;
    }
    .form-control-tech[readonly] {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #64748b !important;
        font-weight: 600;
    }

    /* Tombol Utama Navy */
    .btn-tech-navy {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 10px 24px !important;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-tech-navy:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        box-shadow: 0 6px 18px rgba(15, 43, 92, 0.23);
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    /* Tombol Outline Light */
    .btn-outline-tech {
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-outline-tech:hover {
        background: #ffffff !important;
        color: #0a192f !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Lencana Status */
    .badge-status-admin {
        background-color: #10b981 !important; /* Emerald soft */
        color: #ffffff;
        font-weight: 600;
        padding: 6px 14px !important;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
    }

    /* Informasi Sistem List Group */
    .sys-info-item {
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 0;
    }
    .sys-info-item:last-child {
        border-bottom: none;
    }
</style>

<div class="container container-custom-profile py-4">
    <h3 class="mb-4 fw-bold" style="color: #0a192f;">
        <i class="bi bi-person-gear me-2" style="color: #0f2b5c;"></i>info akun
    </h3>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #f0fdf4; color: #166534; border-left: 4px solid #10b981 !important; border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important; border-radius: 10px;">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Periksa kembali inputan Anda:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row row-gap-4">
        {{-- SEKTOR KIRI: FORM DATA PRIBADI & KEAMANAN --}}
        <div class="col-md-8">
            <!-- Card Data Pribadi -->
            <div class="card card-tech-profile mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold m-0" style="color: #0a192f;">
                        <i class="bi bi-shield-identity me-2" style="color: #0f2b5c;"></i>Data Pribadi
                    </h5>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control form-control-tech" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <input type="email" name="email" class="form-control form-control-tech" value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Role Sistem</label>
                            <input type="text" class="form-control form-control-tech" value="{{ strtoupper(Auth::user()->role) }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-tech-navy px-4">Perbarui Profil</button>
                    </form>
                </div>
            </div>
         
            <!-- Card Keamanan Akun -->
            <div class="card card-security-tech">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-white"><i class="bi bi-key me-2 text-info"></i>Keamanan Akun</h5>
                            <p class="small text-white-50 mb-0">Pastikan password Anda kuat untuk menjaga panel tetap aman.</p>
                        </div>
                        <button type="button" class="btn btn-outline-tech px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalPassword">
                            Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEKTOR KANAN: RINGKASAN AKUN & SISTEM --}}
        <div class="col-md-4">
            <!-- Card Ringkasan Profil Singkat -->
            <div class="card card-tech-profile mb-4 text-center p-4">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3 avatar-tech-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1" style="color: #0a192f;">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                <div>
                    <span class="badge rounded-pill badge-status-admin"><i class="bi bi-patch-check-fill me-1"></i>Administrator Aktif</span>
                </div>
            </div>

            <!-- Card Informasi Detail Sistem -->
            <div class="card card-tech-profile p-4 bg-light border-0">
                <h6 class="fw-bold small mb-3 text-uppercase" style="color: #0f2b5c; letter-spacing: 0.5px;">
                    <i class="bi bi-cpu me-2"></i>Informasi Sistem
                </h6>
                <div class="d-flex justify-content-between sys-info-item">
                    <span class="small text-muted">Terakhir Login:</span>
                    <span class="small fw-bold text-dark">{{ Auth::user()->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between sys-info-item">
                    <span class="small text-muted">Versi Web:</span>
                    <span class="small fw-bold text-dark">v1.2.0-stable</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UBAH PASSWORD --}}
<div class="modal fade text-dark" id="modalPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="modal-header text-white border-0 p-3" style="background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%);">
                    <h5 class="modal-title fw-bold fs-6"><i class="bi bi-shield-lock me-2"></i>Ubah Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Password Lama</label>
                        <input type="password" name="current_password" class="form-control form-control-tech" required placeholder="••••••••">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Password Baru</label>
                        <input type="password" name="new_password" class="form-control form-control-tech" required placeholder="Min. 8 karakter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control form-control-tech" required placeholder="Ulangi password baru">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tech-navy px-4" style="border-radius: 8px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection