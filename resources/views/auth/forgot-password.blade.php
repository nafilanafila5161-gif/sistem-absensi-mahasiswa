<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Gradasi body menggunakan warna Biru Navy Teknik & Efek Mesh Grid Blueprint */
        body { 
            background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 50%, #174694 100%); 
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            background-size: 28px 28px;
            min-height: 100vh;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* Mengoptimalkan kartu dengan efek premium & Glow Top-Bar */
        .card-custom { 
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 20px; 
            max-width: 450px; 
            width: 100%;
            margin: 60px auto; 
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45) !important;
            background-color: rgba(255, 255, 255, 0.97); 
            backdrop-filter: blur(16px);
            overflow: hidden;
        }

        /* Garis dekorasi teknologi presisi di bagian atas kartu */
        .card-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0a192f, #174694, #00f2fe);
        }

        /* Memperhalus input box */
        .form-control-tech {
            border: 1px solid #cbd5e1; 
            border-radius: 10px;
            padding: 10px 14px;
            color: #0a192f;
            font-weight: 500;
            transition: all 0.25s ease-in-out;
        }

        /* Efek saat input diklik (fokus) - Menggunakan aksen Navy Teknik */
        .form-control-tech:focus { 
            border-color: #0f2b5c !important; 
            box-shadow: 0 0 0 0.25rem rgba(15, 43, 92, 0.15) !important; 
            color: #0a192f;
        }

        /* Tombol submit kustom dengan efek gradasi interaktif */
        .btn-tech-navy {
            background: linear-gradient(135deg, #0a192f 0%, #0f2b5c 100%);
            border: none;
            color: #ffffff;
            border-radius: 10px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-tech-navy:hover {
            background: linear-gradient(135deg, #0f2b5c 0%, #174694 100%);
            box-shadow: 0 8px 22px rgba(15, 43, 92, 0.35);
            transform: translateY(-1px);
            color: #ffffff;
        }
        .btn-tech-navy:active {
            transform: translateY(1px);
        }

        /* Tombol Batal / Kembali bergaya minimalis */
        .btn-tech-link {
            color: #64748b;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-tech-link:hover {
            color: #0f2b5c;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container py-4">
        <div class="card card-custom p-4 p-sm-5 shadow">
            
            <!-- Header Tampilan -->
            <div class="text-center mb-4">
                <div class="mb-2">
                    <i class="bi bi-shield-key-fill" style="font-size: 2.5rem; background: linear-gradient(135deg, #0a192f, #174694); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <h4 class="fw-bold m-0" style="color: #0a192f; letter-spacing: -0.5px;">Atur Ulang Password</h4>
                <p class="text-center text-muted small mt-1 mb-0">Silakan masukkan email dan konfirmasi sandi baru Anda.</p>
            </div>
            
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                
                <!-- Input Email Akun -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email Akun</label>
                    <input type="email" name="email" class="form-control form-control-tech" placeholder="nama@email.com" required>
                    @error('email') 
                        <div class="d-flex align-items-center text-danger mt-1 small fw-medium">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div> 
                    @enderror
                </div>
                
                <!-- Input Password Baru -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password Baru</label>
                    <input type="password" name="password" class="form-control form-control-tech" placeholder="Minimal 8 karakter" required>
                </div>
                
                <!-- Input Ulangi Password Baru -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Ulangi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-tech" placeholder="Konfirmasi password baru" required>
                </div>
                
                <!-- Tombol Eksekusi -->
                <button type="submit" class="btn btn-tech-navy w-100 fw-bold py-2-5" style="letter-spacing: 0.5px;">SIMPAN PASSWORD BARU</button>
                
                <!-- Navigasi Cerdas yang Dipercantik -->
                <div class="text-center mt-4">
                    @auth
                        <a href="{{ route('settings.show') }}" class="btn-tech-link small">
                            <i class="bi bi-arrow-left"></i> Batal & Kembali
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn-tech-link small">
                            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    @endauth
                </div>
            </form>
        </div>
    </div>
</body>
</html>