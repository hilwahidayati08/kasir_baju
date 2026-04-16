<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ Auth::user()->role == 'Admin' ? route('dashboard.admin') : '#' }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/avatars/elzatta-logo.webp') }}" alt="Logo" style="height: 40px; width: auto;">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ================= ADMIN ================= --}}
        @if (Auth::user()->role == "Admin")
            <li class="menu-item">
                <a href="{{ route('dashboard.admin') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Dashboard">Halaman Utama</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-data"></i>
                    <div data-i18n="Data Master">Data Utama</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="{{ route('branch.index') }}" class="menu-link"><div data-i18n="Kategori">Cabang</div></a></li>
                    <li class="menu-item"><a href="{{ route('categories.index') }}" class="menu-link"><div data-i18n="Kategori">Kategori</div></a></li>
                    <li class="menu-item"><a href="{{ route('variant.index') }}" class="menu-link"><div data-i18n="Varian">Varian</div></a></li>
                    <li class="menu-item"><a href="{{ route('products.index') }}" class="menu-link"><div data-i18n="Produk">Produk</div></a></li>
                    <li class="menu-item"><a href="{{ route('stocks.index') }}" class="menu-link"><div data-i18n="Stock">Stock</div></a></li>
                </ul>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen</span></li>
            <li class="menu-item"><a href="{{ route('users.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-user"></i><div data-i18n="Pengguna">Pengguna</div></a></li>
            <li class="menu-item"><a href="{{ route('customers.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-group"></i><div data-i18n="Customer">Pelanggan</div></a></li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span></li>
            <li class="menu-item"><a href="{{ route('report.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-file"></i><div data-i18n="Laporan">Laporan</div></a></li>
        @endif

        {{-- ================= KASIR ================= --}}
        @if (Auth::user()->role == "Cashier")

            <li class="menu-item"><a href="{{ route('sales.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-edit-alt"></i><div data-i18n="Sales">Penjualan</div></a></li>

        @endif

        {{-- ================= CABANG ================= --}}
        @if (Auth::user()->role == "Cabang")
            <li class="menu-item">
                <a href="{{ route('dashboard.branch') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Dashboard">Dashboard Cabang</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaksi & Request</span></li>
            <li class="menu-item"><a href="{{ route('requests.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-edit-alt"></i><div data-i18n="Request">Request Stok</div></a></li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Data & Laporan</span></li>
            <li class="menu-item"><a href="{{ route('stocks.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-archive"></i><div data-i18n="Stock">Stok</div></a></li>
            <li class="menu-item"><a href="{{ route('report.index') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-file"></i><div data-i18n="Laporan">Laporan</div></a></li>
        @endif
            <li class="menu-item"><a href="{{ route('logout') }}" class="menu-link"><i class="menu-icon tf-icons bx bx-power-off me-2"></i><div data-i18n="Laporan">Keluar</div></a></li>

    </ul>


</aside>
