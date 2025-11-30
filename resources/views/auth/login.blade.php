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
            display: flex;
            justify-content: center;
            align-items: center;
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
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #141414;
            border-radius: 12px;
            border: none;
            color: #fff;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #000;
        }

        .register-btn {
            margin-top: 12px;
            background: transparent;
            border: 1px solid #000;
            padding: 10px;
            width: 100%;
            border-radius: 12px;
            font-weight: 600;
            color: #000;
            text-decoration: none;
        }

        .register-btn {
            margin-top: 15px;
            display: block;
            text-align: center;
        }

        .header-icon {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 12px;
            font-size: 22px;
            color: #000;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .text-small {
            font-size: 13px;
            color: #444;
        }
    </style>

</head>
<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="login-card">

            <div class="text-center mb-4">
                <img src="/storage/logo.png" width="70">
            </div>

            <h3 class="text-center mb-2">Sign in with email</h3>
            <p class="text-center text-muted">Login to access menu management</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label>Email</label>
                <input type="email" name="email" class="form-control mb-3" required>

                <label>Password</label>
                <input type="password" name="password" class="form-control mb-3" required>

                <button class="btn btn-dark w-100">Get Started</button>

                <a href="{{ route('register') }}" class="register-btn">Register</a>
            </form>

        </div>
    </div>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
