<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <x-nav-link :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge me-3"></i> Dashboard
                </x-nav-link>
                <x-nav-link :active="request()->routeIs('list-setor')" href="{{ route('list-setor') }}">
                    <i class="fa-solid fa-rectangle-list me-3"></i>Setor Sampah
                </x-nav-link>
                <x-nav-link :active="request()->routeIs('list-tarik-saldo')" href="{{ route('list-tarik-saldo') }}">
                    <i class="fa-solid fa-money-check-dollar me-3"></i>Tarik Saldo
                </x-nav-link>
                <x-nav-link :active="request()->routeIs('jenis-sampah')" href="{{ route('jenis-sampah') }}">
                    <i class="fa-solid fa-dumpster me-3"></i>Jenis Sampah
                </x-nav-link>
                <x-nav-link :active="request()->routeIs('list-nasabah')" href="{{ route('list-nasabah') }}">
                    <i class="fa-solid fa-users me-3"></i>Daftar Nasabah
                </x-nav-link>
            </div>
        </div>
    </nav>
</div>
