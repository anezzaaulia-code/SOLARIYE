<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir</title>

    <!-- TAILWIND CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen">

        <!-- SIDEBAR -->
        <aside class="w-60 h-full bg-gray-900 text-white p-5">
            <h4 class="text-xl font-bold mb-5">Menu Kasir</h4>

            <ul class="space-y-3">
                <li>
                    <a href="{{ route('kasir.dashboard') }}"
                        class="block py-2 px-3 rounded hover:bg-gray-700">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('kasir.riwayat') }}"
                        class="block py-2 px-3 rounded hover:bg-gray-700">
                        Riwayat
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN -->
        <main class="flex-1 p-5 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</body>
</html>
