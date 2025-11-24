<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #c471f5, #fa71cd, #6bb9f0);
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial';
        }

        .login-card {
            width: 360px;
            padding: 35px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(18px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .login-card input {
            background: rgba(0, 0, 0, 0.25);
            border: none;
            color: white;
        }

        .login-card input::placeholder {
            color: #ddd;
        }

        .login-card input:focus {
            background: rgba(0, 0, 0, 0.35);
            color: #fff;
        }

        .profile-icon {
            width: 80px;
            height: 80px;
            margin: auto;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 38px;
            margin-bottom: 20px;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.25);
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        a {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="login-card">

        <div class="profile-icon">
            <i class="bi bi-person" style="font-size: 42px;"></i>
        </div>

        <h4 class="text-center mb-4">LOGIN</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email ID" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div>
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
        </form>

        <p class="mt-3 text-center">
            Don't have an account? <a href="{{ route('register') }}"><strong>Register</strong></a>
        </p>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
