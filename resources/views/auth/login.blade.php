<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-custom { border: 2px solid #000; border-radius: 12px; max-width: 400px; width: 100%; margin: auto; }
        .input-custom { border: 1px solid #000; }
    </style>
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container d-flex justify-content-center">
        <div class="card card-custom p-4 shadow-sm">
            
            @if ($errors->any())
                <div class="alert alert-danger p-2 small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control input-custom" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="pass" class="form-control input-custom" required>
                        <span class="input-group-text bg-white border-dark" onclick="toggle()" style="cursor:pointer">
                            <i class="bi bi-eye" id="icon"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input border-dark" id="rem">
                        <label class="form-check-label" for="rem">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-dark text-decoration-none small">Lupa pass?</a>
                </div>

                <button type="submit" class="btn btn-dark w-100">LOGIN</button>

               
            </form> 
            </div>
    </div>

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