<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SOLARIYE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('{{ asset("storage/login/clouds.jpg") }}') no-repeat center center fixed;
            background-size: cover;
        }

        .register-card {
            width: 420px;
            padding: 35px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }

        .register-card input,
        .register-card select {
            border-radius: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
        }

        .register-card input:focus,
        .register-card select:focus {
            background: white;
            border-color: #6cb4ff;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #141414;
            border-radius: 12px;
            border: none;
            color: #fff;
            font-weight: 600;
        }

        .btn-register:hover {
            background-color: #000;
        }

        .login-btn {
            margin-top: 15px;
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            border-radius: 12px;
            background: transparent;
            border: 1px solid #000;
            font-weight: 600;
            color: #000;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="register-card">

            <div class="text-center mb-4">
                <img src="/storage/login/logo.png" width="70">
            </div>

            <h3 class="text-center mb-2">Create Your Account</h3>
            <p class="text-center text-muted">Register to access menu management</p>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- Nama --}}
                <label class="mb-1">Nama</label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama') }}"
                       class="form-control mb-3 @error('nama') is-invalid @enderror"
                       required>
                @error('nama')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                {{-- Email --}}
                <label class="mb-1">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control mb-3 @error('email') is-invalid @enderror"
                       required>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                {{-- Role --}}
                <label class="mb-1">Role</label>
                <select name="role" class="form-select mb-3 @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                {{-- Password --}}
                <label class="mb-1">Password</label>
                <input type="password"
                       name="password"
                       class="form-control mb-3 @error('password') is-invalid @enderror"
                       required>
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                {{-- Konfirmasi Password --}}
                <label class="mb-1">Konfirmasi Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control mb-3"
                       required>

                <button type="submit" class="btn-register">Register</button>

                <a href="{{ route('login') }}" class="login-btn">Back to Login</a>

            </form>

        </div>
    </div>

</body>
</html>
