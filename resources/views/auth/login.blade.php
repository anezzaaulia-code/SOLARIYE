<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center"
         style="min-height: 100vh;">
         
        <div class="card shadow p-4" style="width: 380px;">
            <h4 class="mb-3 text-center">Login</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password"
                        class="form-control" required>
                </div>

                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
        </div>

    </div>
</body>
</html>
