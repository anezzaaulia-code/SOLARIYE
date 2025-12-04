<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .wrapper {
            display: flex;
            height: 100%;
            width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: #212529;
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #343a40;
            color: white;
            border-left: 4px solid #0d6efd;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 15px;
            border-top: 1px solid #343a40;
        }

        /* MAIN */
        .main-panel {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .top-navbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #ced4da; border-radius: 10px; }
    </style>
</head>
<body>

<div class="wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="d-flex align-items-center justify-content-center py-3 border-bottom">
            <h4 class="fw-bold text-primary m-0">
                <i class="bi bi-shop me-2"></i>Kasir App
            </h4>
        </div>

        <nav class="mt-3 flex-grow-1">
            <a href="{{ route('kasir.dashboard') }}"
                class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Transaksi POS
            </a>

            <a href="{{ route('kasir.riwayat') }}"
                class="nav-link {{ request()->routeIs('kasir.riwayat') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
            <div class="text-center small text-muted mt-2">
                &copy; {{ date('Y') }} Kasir App
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-panel">

        <header class="top-navbar shadow-sm">
            <h5 class="fw-bold m-0 text-dark">
                @yield('title', 'Kasir App')
            </h5>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-success">
                        <i class="bi bi-circle-fill" style="font-size:8px"></i> Online
                    </small>
                </div>

                <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center"
                     style="width:40px; height:40px;">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
