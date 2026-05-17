<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { 
            border: 1px solid #dee2e6; 
            border-radius: 15px; 
            max-width: 450px; 
            margin: 80px auto; 
            border-top: 5px solid #212529;
        }
        .form-control:focus { border-color: #212529; box-shadow: none; }
    </style>
</head>
<body>
    <div class="card p-4 shadow">
        <h4 class="text-center mb-2 fw-bold">Atur Ulang Password</h4>
        <p class="text-center text-muted small mb-4">Silakan masukkan email dan password baru Anda.</p>
        
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Akun</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
            </div>
            
            <button type="submit" class="btn btn-dark w-100 fw-bold py-2">SIMPAN PASSWORD BARU</button>
            
            <div class="text-center mt-4">
                {{-- LOGIKA NAVIGASI CERDAS --}}
               <div class="text-center mt-3">
    @auth
       <a href="{{ route('settings.show') }}" class="btn btn-secondary">Batal</a>
    @else
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    @endauth
</div>
            </div>
        </form>
    </div>
</body>
</html>