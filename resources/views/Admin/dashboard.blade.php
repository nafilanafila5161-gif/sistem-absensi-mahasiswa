@extends('layouts.admin')

@section('content')
<!-- Tambahan Style untuk Menyelaraskan Tema Biru Navy Teknik Tingkat Lanjut -->
<style>
    /* Membuat latar belakang area konten memiliki tekstur cetak biru teknik yang samar & estetik */
    .container-fluid {
        position: relative;
        background-image: 
            linear-gradient(rgba(15, 43, 92, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 43, 92, 0.02) 1px, transparent 1px);
        background-size: 20px 20px;
        min-height: 100vh;
    }

    .card-stat {
        position: relative;
        border: 1px solid rgba(15, 43, 92, 0.08); 
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.03) !important;
        overflow: hidden;
    }
    
    /* Garis dekoratif atas (Glow Bar) ciri khas dashboard engineering modern */
    .card-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #0a192f, #174694);
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    /* Efek hover dinamis & bersinar (Glow effect) */
    .card-stat:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(15, 43, 92, 0.12) !important;
        border-color: rgba(23, 70, 148, 0.3);
    }

    .card-stat:hover::before {
        opacity: 1;
    }
    
    /* Wadah ikon dengan gradasi melingkar tech-style */
    .icon-shape {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #eef4fc 0%, #d8e7f9 100%);
        color: #0f2b5c; 
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        border: 1px solid rgba(23, 70, 148, 0.1);
        transition: all 0.3s ease;
    }

    .card-stat:hover .icon-shape {
        background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%);
        color: #ffffff;
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 8px 20px rgba(15, 43, 92, 0.2);
    }
    
    /* Tipografi presisi */
    .title-dashboard {
        color: #0a192f;
        font-weight: 800;
        letter-spacing: -0.5px;
        position: relative;
    }
    
    .stat-number {
        color: #0a192f;
        letter-spacing: -1px;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.8rem;
        letter-spacing: 0.8px;
    }
</style>

<div class="container-fluid py-4">
    <h2 class="mb-4 title-dashboard">Dashboard Utama Admin</h2>
    
    <div class="row g-4"> <!-- Jarak antar kartu seimbang -->
        <!-- Card Total Mahasiswa -->
        <div class="col-md-6">
            <div class="card card-stat p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1 stat-label">Total Mahasiswa</h6>
                        <h2 class="fw-bold m-0 stat-number" style="font-size: 2.75rem;">{{ $stats['total_mahasiswa'] }}</h2>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    <span class="fw-semibold" style="color: #10b981;"><i class="bi bi-arrow-up-short"></i> Terdaftar</span> aktif di sistem
                </div>
            </div>
        </div>
        
        <!-- Card Total Dosen -->
        <div class="col-md-6">
            <div class="card card-stat p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-1 stat-label">Total Dosen</h6>
                        <h2 class="fw-bold m-0 stat-number" style="font-size: 2.75rem;">{{ $stats['total_dosen'] }}</h2>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    <span class="fw-semibold" style="color: #10b981;"><i class="bi bi-arrow-up-short"></i> Terdaftar</span> aktif di sistem
                </div>
            </div>
        </div>
    </div>
</div>
@endsection