@extends('layouts.admin')

@section('content')
<!-- Tambahan Style Premium - Tema Cyber Navy Engineering -->
<style>
    /* Efek blueprint mesh grid pada background agar nuansa teknik makin berasa */
    .container-fluid {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.015) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.015) 1px, transparent 1px);
        background-size: 24px 24px;
        min-height: 100vh;
    }

    /* Kartu Utama dengan Glow Top-Bar & Soft Shadow */
    .card-profile-custom {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08) !important;
        border-radius: 20px !important;
        box-shadow: 0 15px 40px rgba(10, 25, 47, 0.04) !important;
        background: #ffffff;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    /* Garis dekorasi teknologi presisi di bagian atas kartu */
    .card-profile-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
    }

    /* Komponen Avatar / Inisial Nama Bulat */
    .avatar-tech-navy {
        width: 90px; 
        height: 90px; 
        font-size: 2.25rem; 
        font-weight: 800;
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        color: #ffffff !important;
        border: 4px solid rgba(23, 70, 148, 0.1);
        box-shadow: 0 8px 20px rgba(15, 43, 92, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    .card-profile-custom:hover .avatar-tech-navy {
        transform: scale(1.03);
    }

    /* Tombol Aksi Utama Bergradasi Metalik Modern */
    .btn-tech-navy {
        background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%) !important;
        border: none !important;
        color: #ffffff !important;
        border-radius: 12px !important;
        padding: 10px 24px !important;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .btn-tech-navy:hover {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%) !important;
        box-shadow: 0 8px 20px rgba(15, 43, 92, 0.25) !important;
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    /* Wadah Baris Data Profil Berwarna Kotak Tipis (Glow Border Left) */
    .profile-data-box {
        background: rgba(248, 250, 252, 0.6);
        border-left: 3px solid #174694;
        border-radius: 0 10px 10px 0;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }
    .profile-data-box:hover {
        background: rgba(15, 43, 92, 0.02);
        border-left-color: #00f2fe; /* Berubah cyan menyala saat di-hover */
    }

    /* Lencana / Badge Bergaya Dashboard Tech */
    .badge-navy-role {
        background: #0f2b5c !important;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        padding: 6px 14px !important;
        border-radius: 6px !important;
        box-shadow: 0 4px 10px rgba(15, 43, 92, 0.1);
    }

    .badge-status-active {
        background: #10b981 !important;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 6px 14px !important;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
    }

    /* Tipografi Utama */
    .profile-name-header {
        color: #0a192f;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .label-tech {
        color: #64748b !important;
        font-size: 0.7rem !important;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .value-tech {
        color: #0a192f;
        font-weight: 600;
        margin-bottom: 0;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-profile-custom">
                <!-- Header Kartu -->
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 fw-bold" style="color: #0a192f;">
                        <i class="bi bi-person-badge me-2" style="color: #0f2b5c;"></i>Informasi Profil
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <!-- Sektor Atas: Ringkasan Akun -->
                    <div class="row align-items-center mb-4 row-gap-3">
                        <div class="col-auto">
                            <div class="rounded-circle shadow-sm avatar-tech-navy">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1 profile-name-header">{{ $user->name }}</h4>
                            <p class="text-muted mb-2 small"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                            <div class="d-flex gap-2">
                                <span class="badge badge-status-active"><i class="bi bi-check-circle-fill me-1"></i>Status: Aktif</span>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <!-- Sektor Bawah: Grid Spesifikasi Data -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="profile-data-box">
                                <label class="label-tech text-uppercase fw-bold d-block">Nama Lengkap</label>
                                <p class="fs-6 value-tech">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="profile-data-box">
                                <label class="label-tech text-uppercase fw-bold d-block">Alamat Email</label>
                                <p class="fs-6 value-tech">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="profile-data-box">
                                <label class="label-tech text-uppercase fw-bold d-block">Role Sistem</label>
                                <div class="mt-1">
                                    <span class="badge badge-navy-role text-uppercase"><i class="bi bi-shield-lock me-1"></i>{{ $user->role }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="profile-data-box">
                                <label class="label-tech text-uppercase fw-bold d-block">Bergabung Sejak</label>
                                <p class="fs-6 value-tech text-secondary"><i class="bi bi-calendar3 me-1 small"></i>{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 pt-2">
                        <a href="{{ route('settings.show') }}" class="btn btn-tech-navy shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection