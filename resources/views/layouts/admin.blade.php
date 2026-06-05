<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiAbsensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background: #f4f7f6; font-family: sans-serif; }
        .sidebar { min-height: 100vh; background: #212529; color: white; padding: 20px; position: fixed; width: 16%; z-index: 1000; }
        .main-content { margin-left: 16%; min-height: 100vh; }
        .top-nav { background: white; padding: 15px 30px; display: flex; justify-content: flex-end; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .profile-img { width: 35px; height: 35px; border-radius: 50%; background: #0d6efd; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; }
        .nav-link { color: rgba(255,255,255,0.8); margin-bottom: 5px; border-radius: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar h4 { padding-left: 10px; margin-bottom: 1.5rem; color: #0d6efd; font-weight: bold; }
    </style>
</head>
<body>
    <div class="sidebar">
    <h4>SiAbsensi</h4>
    <nav class="nav flex-column mt-4">
        
        @if(Auth::user()->role == 'admin')
            <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard Admin
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people"></i> Kelola Users
            </a>
        @endif

        @if(Auth::user()->role == 'mahasiswa')
            <a class="nav-link text-white {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" href="{{ route('mahasiswa.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard Absensi
            </a>
            <a class="nav-link text-white {{ request()->routeIs('mahasiswa.rekap') ? 'active' : '' }}" href="{{ route('mahasiswa.rekap') }}">
                <i class="fas fa-fw fa-history"></i> Rekap Absensi
            </a>
        @endif

        @if(Auth::user()->role == 'dosen')
    <a class="nav-link text-white {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}" href="{{ route('dosen.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard Dosen
    </a>

    

    <a class="nav-link text-white {{ request()->routeIs('dosen.rekap') ? 'active' : '' }}" href="{{ route('dosen.rekap') }}">
        <i class="fas fa-fw fa-table me-2"></i> Rekap Absensi Mahasiswa
    </a>
@endif

    </nav>
</div>

    <div class="main-content">
        <header class="top-nav">
            <div class="dropdown">
                <div class="profile-img dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="{{ route('settings.show') }}"><i class="bi bi-gear me-2"></i> Info akun</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="p-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
        });
    @endif
</script>
@endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>