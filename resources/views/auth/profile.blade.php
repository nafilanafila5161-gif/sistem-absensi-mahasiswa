@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            <span class="badge rounded-pill bg-success px-3">Status: Aktif</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Nama Lengkap</label>
                            <p class="fs-5 border-bottom pb-2">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Alamat Email</label>
                            <p class="fs-5 border-bottom pb-2">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Role Sistem</label>
                            <div>
                                <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Bergabung Sejak</label>
                            <p class="fs-6">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                      <a href="{{ route('settings') }}" ...>

<a href="{{ route('settings.show') }}" class="btn btn-dark px-4">
    <i class="bi bi-pencil-square me-2"></i>Edit Profil
</a>
                           
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
@endsection