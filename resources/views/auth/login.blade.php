<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SOLARIYE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Pastikan gambar clouds.jpg ada di public/storage/login/ */
            background: url('/storage/login/clouds.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-card {
            width: 420px;
            padding: 35px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }

        .login-card input {
            border-radius: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
        }

        .login-card input:focus {
            background: white;
            border-color: #6cb4ff;
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #141414;
            border-radius: 12px;
            border: none;
            color: #fff;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #333;
        }

        /* Pesan Error Login */
        .alert-danger {
            font-size: 14px;
            border-radius: 10px;
        }
    </style>

</head>
<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="login-card">

            <div class="text-center mb-4">
                {{-- Pastikan logo ada --}}
                <img src="/storage/logo.png" width="70" onerror="this.style.display='none'">
            </div>

            <h3 class="text-center mb-2">Welcome Back</h3>
            <p class="text-center text-muted mb-4">Login to access POS System</p>

            {{-- Tampilkan Error jika email/password salah --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button class="btn btn-login">Sign In</button>

                {{-- TOMBOL REGISTER SUDAH DIHAPUS DEMI KEAMANAN --}}
            </form>

        </div>
    </div>

</body>
</html>