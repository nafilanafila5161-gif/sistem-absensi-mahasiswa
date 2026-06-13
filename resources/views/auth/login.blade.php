<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Mengoptimalkan kartu login dengan efek premium & Glow Top-Bar */
        .card-custom { 
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 20px; 
            max-width: 420px; 
            width: 100%; 
            margin: auto; 
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45) !important;
            background-color: rgba(255, 255, 255, 0.97); 
            backdrop-filter: blur(16px);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Garis dekorasi teknologi presisi di bagian atas kartu login */
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
        .input-custom { 
            border: 1px solid #cbd5e1; 
            border-radius: 10px;
            padding: 10px 14px;
            color: #0a192f;
            font-weight: 500;
            transition: all 0.25s ease-in-out;
        }

        /* Efek saat input diklik (fokus) - Menggunakan aksen Navy Teknik yang tajam */
        .input-custom:focus {
            border-color: #0f2b5c !important;
            box-shadow: 0 0 0 0.25rem rgba(15, 43, 92, 0.15) !important;
            color: #0a192f;
        }

        /* Menyelaraskan border input group untuk password */
        .input-group .input-custom {
            border-right: none;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .input-group-text-custom {
            border: 1px solid #cbd5e1;
            border-left: none;
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            transition: all 0.25s ease-in-out;
        }
        
        /* Efek sinkronisasi warna border saat berinteraksi */
        .input-group:focus-within .input-custom {
            border-color: #0f2b5c;
        }
        .input-group:focus-within .input-group-text-custom {
            border-color: #0f2b5c;
            box-shadow: 0 0 0 0.25rem rgba(15, 43, 92, 0.15) !important;
            /* Potong bayangan bagian kiri agar tidak menabrak input */
            clip-path: inset(-10px -10px -10px 0px); 
        }

        /* Modifikasi Checkbox Kustom bertema Navy */
        .form-check-input-custom:checked {
            background-color: #0f2b5c !important;
            border-color: #0f2b5c !important;
        }
        .form-check-input-custom:focus {
            box-shadow: 0 0 0 0.25rem rgba(15, 43, 92, 0.15) !important;
            border-color: #0f2b5c;
        }

        /* Efek hover link agar berubah ke warna biru navy teknik */
        .hover-link {
            transition: color 0.2s ease;
        }
        .hover-link:hover {
            text-decoration: underline !important;
            color: #0f2b5c !important;
        }

        /* Tombol login kustom dengan efek gradasi interaktif */
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

        /* Kustomisasi Tampilan Error Alert */
        .alert-custom-danger {
            background-color: #fef2f2 !important;
            border-left: 4px solid #ef4444 !important;
            color: #991b1b !important;
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container d-flex justify-content-center py-4">
        <div class="card card-custom p-4 p-sm-5">
            
            <!-- Header Form Login -->
            <div class="text-center mb-4">
                <div class="mb-2">
                    <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem; background: linear-gradient(135deg, #0a192f, #174694); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <h3 class="fw-bold m-0" style="color: #0a192f; letter-spacing: -0.5px;">Selamat Datang</h3>
                <p class="text-muted small mt-1 mb-0">Silakan masuk ke akun panel kendali Anda</p>
            </div>
            
            {{-- Alert Error bawaan Laravel --}}
            @if ($errors->any())
                <div class="alert alert-custom-danger p-3 small border-0 mb-4 animate-fade">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <!-- Input Email -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                    <input type="email" name="email" class="form-control input-custom py-2" required placeholder="nama@email.com">
                </div>

                <!-- Input Password -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="pass" class="form-control input-custom py-2" required placeholder="••••••••">
                        <span class="input-group-text bg-white input-group-text-custom" onclick="toggle()" style="cursor:pointer">
                            <i class="bi bi-eye text-secondary" id="icon"></i>
                        </span>
                    </div>
                </div>

                <!-- Fitur Tambahan (Remember Me & Lupa Password) -->
                <div class="d-flex justify-content-between mb-4 align-items-center">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input form-check-input-custom" id="rem">
                        <label class="form-check-label small text-secondary fw-medium" for="rem" style="cursor: pointer;">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-secondary text-decoration-none small fw-medium hover-link">Lupa password?</a>
                </div>

                <!-- Tombol Submit Login -->
                <button type="submit" class="btn btn-tech-navy w-100 py-2M py-2-5 fw-bold shadow-sm" style="letter-spacing: 0.5px;">MASUK</button>
               
            </form> 
        </div>
    </div>

    <!-- Script fungsional pembalik visibilitas teks sandi -->
    <script>
        function toggle() {
            let p = document.getElementById('pass');
            let i = document.getElementById('icon');
            p.type = (p.type === "password") ? "text" : "password";
            i.classList.toggle('bi-eye');
            i.classList.toggle('bi-eye-slash');
        }
    </script>
</body>
</html>