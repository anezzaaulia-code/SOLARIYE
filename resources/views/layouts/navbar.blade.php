<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="#">Aplikasi Laravel</a>

        <!-- Tombol toggle mobile -->
        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Isi Navbar -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            {{-- Menu kiri --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('tampil-kategori') }}">Kategori</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('laporan') }}">Laporan</a>
                </li>

            </ul>

            {{-- Menu kanan (autentikasi user) --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                @auth
                <li class="nav-item dropdown">

                    <button class="btn btn-light dropdown-toggle" 
                            type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" 
                        aria-labelledby="dropdownMenuButton">

                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>

                    </ul>

                </li>

                @else

                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                </li>

                @endauth

            </ul>

        </div>
    </div>
</nav>
