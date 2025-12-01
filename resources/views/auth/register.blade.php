<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SOLARIYE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset('storage/login/clouds.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
        }

        .logo-img {
            width: 90px;
            display: block;
            margin: 0 auto 10px auto;
        }
    </style>
</head>

<body>

    <div class="auth-card">

        {{-- Logo (opsional, kalau login juga pakai) --}}
        {{-- <img src="{{ asset('storage/login/logo.png') }}" class="logo-img"> --}}

        <h3 class="text-center mb-3">Register Akun</h3>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" 
                       name="nama" 
                       value="{{ old('nama') }}"
                       class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" 
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" 
                       name="password_confirmation" 
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>
        </form>

        <p class="mt-3 text-center">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>

</body>
</html>
