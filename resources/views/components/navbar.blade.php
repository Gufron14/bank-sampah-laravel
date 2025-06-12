<nav class="navbar navbar-expand-lg sticky-top bg-success" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">Bank Sampah</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <x-nav-link :active="request()->routeIs('/')" href="/">Home</x-nav-link>
                <x-nav-link :active="request()->routeIs('setor-sampah')" href="{{ route('setor-sampah') }}">Setor Sampah</x-nav-link>
                <x-nav-link :active="request()->routeIs('riwayat')" href="{{ route('riwayat') }}">Riwayat</x-nav-link>
            </ul>
            <div class="d-flex gap-2">
                @auth
                    <!-- Avatar Dropdown Component -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                            id="avatarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
<img src="{{ $avatarPreview ?? auth()->user()->avatar_url }}" alt="Avatar"
                             class="rounded-circle shadow" width="36" height="36">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profil') }}">Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-light fw-bold">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">Daftar</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
