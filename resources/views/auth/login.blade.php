<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EMKL Automation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #F8FAFC;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: white;
        }
        .btn-primary {
            background-color: #2563EB;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1E293B;
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <i class="bi bi-ship-wheel text-primary me-2"></i>EMKL<span class="text-primary">SYS</span>
        </div>
        <h5 class="fw-bold mb-3 text-center">Selamat Datang</h5>
        <p class="text-muted text-center small mb-4">Silakan masukkan akun Anda untuk mengakses dashboard.</p>

        @if($errors->any())
            <div class="alert alert-danger small py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@emkl.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Masuk ke Dashboard</button>
            <div class="text-center">
                <a href="#" class="text-decoration-none small text-muted">Lupa Kata Sandi?</a>
            </div>
        </form>
    </div>
</body>
</html>
