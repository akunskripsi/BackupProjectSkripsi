<ul class="navbar-nav bg-gradient-warning sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Toko Surya Elektrik</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Daftar Menu
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('pembeli*') ? 'active' : '' }}">
        <a class="nav-link" href="/pembeli">
            <i class="fas fa-fw fa-table"></i>
            <span>Data Pembeli</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->is('produk*') ? 'active' : '' }}">
        <a class="nav-link" href="/produk">
            <i class="fas fa-fw fa-table"></i>
            <span>Data Produk</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->is('rating*') ? 'active' : '' }}">
        <a class="nav-link" href="/rating">
            <i class="fas fa-fw fa-table"></i>
            <span>Data Rating/Interaksi Pembeli</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item {{ request()->is('rekomendasi*') ? 'active' : '' }}">
        <a class="nav-link" href="/rekomendasi">
            <i class="fas fa-fw fa-table"></i>
            <span>Rekomendasi</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
