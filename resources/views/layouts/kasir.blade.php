<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - SOLARIYE</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-xl">
            <div class="h-16 flex items-center justify-center bg-gray-800 border-b border-gray-700 shadow-sm">
                <h1 class="text-xl font-bold tracking-wider">SOLARIYE POS</h1>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <a href="{{ route('kasir.pos') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 
                   {{ request()->routeIs('kasir.pos') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-cash-register w-6"></i>
                    <span class="font-medium">Transaksi POS</span>
                </a>

                <a href="{{ route('kasir.riwayat') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 
                   {{ request()->routeIs('kasir.riwayat') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-history w-6"></i>
                    <span class="font-medium">Riwayat Transaksi</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700 bg-gray-800">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                        <i class="fas fa-user text-gray-300"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->nama ?? 'Kasir' }}</p>
                        <p class="text-xs text-green-400">● Online</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
            <header class="h-16 bg-white shadow flex items-center px-6 justify-between z-10">
                <h2 class="text-xl font-bold text-gray-800">
                    @yield('title', 'Dashboard Kasir')
                </h2>
                <div class="text-sm text-gray-500">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </header>

            <div class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')
</body>
</html>