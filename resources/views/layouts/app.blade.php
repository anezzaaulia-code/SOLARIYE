<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>{{ $title ?? 'Solariye' }}</title>

  <!-- Tailwind CDN (cepat & responsif) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* sedikit style tambahan */
    body { background: #f3f4f6; }
    .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background:#fff; border-top: 1px solid #e5e7eb; }
  </style>
</head>
<body class="min-h-screen">

  <!-- header -->
  <header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('storage/logo.png') }}" alt="logo" class="w-10 h-10 object-contain">
        <span class="font-semibold text-lg">Warung Nasgor</span>
      </div>
      <div>
        <a href="{{ url('/') }}" class="text-sm text-gray-700">Home</a>
        <a href="{{ url('/menu') }}" class="ml-4 text-sm text-gray-700">Menu</a>
      </div>
    </div>
  </header>

  <!-- content -->
  <main class="container mx-auto px-4 py-6 mb-24">
    @yield('content')
  </main>

  <!-- bottom nav (mobile) -->
  <nav class="bottom-nav hidden md:flex md:justify-center">
    <div class="container px-4 py-3 flex justify-between max-w-2xl">
      <a href="{{ url('/') }}" class="text-center">Menu</a>
      <a href="{{ url('/keranjang') }}" class="text-center">Keranjang</a>
      <a href="{{ url('/pesanan') }}" class="text-center">Pesanan</a>
    </div>
  </nav>

</body>
</html>
