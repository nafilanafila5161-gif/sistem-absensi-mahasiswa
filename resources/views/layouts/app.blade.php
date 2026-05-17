<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi - {{ ucfirst(Auth::user()->role) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { width: 250px; min-height: 100vh; background: #212529; color: white; position: fixed; }
        .main-content { margin-left: 250px; padding: 20px; width: calc(100% - 250px); }
        .nav-link { color: rgba(255,255,255,.75); }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,.1); }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar p-3">
            <h4 class="text-center mb-4">
                @if(Auth::user()->role == 'admin') Admin Panel 
                @elseif(Auth::user()->role == 'dosen') Area Dosen
                @else Area Mahasiswa @endif
            </h4>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>

                @if(Auth::user()->role == 'admin')
                    <li><a href="{{ route('admin.users.index') }}" class="nav-link"><i class="fas fa-users me-2"></i> Kelola User</a></li>
                @elseif(Auth::user()->role == 'dosen')
                    <li><a href="#" class="nav-link"><i class="fas fa-chalkboard me-2"></i> Kelola Kelas</a></li>
                    <li><a href="{{ route('dosen.absensi.pantau') }}" class="nav-link"><i class="fas fa-qrcode me-2"></i> Sesi Absensi</a></li>
                    <li><a href="#" class="nav-link"><i class="fas fa-file-alt me-2"></i> Rekapan</a></li>
                @else
                    <li><a href="#" class="nav-link"><i class="fas fa-camera me-2"></i> Scan Absensi</a></li>
                    <li><a href="#" class="nav-link"><i class="fas fa-history me-2"></i> Riwayat Kehadiran</a></li>
                @endif
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">@csrf
                            <button class="dropdown-item" type="submit">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content">@yield('content')</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>