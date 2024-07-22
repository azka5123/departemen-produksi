<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-text mx-3">Departemen Produksi</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Data Dashboard Link -->
    <x-sidebar.sidebar-links route="dashboard" baseRoute="dashboard" icon="fas fa-fw fa-tachometer-alt" title="Dashboard" />

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    @if (Auth::user()->role == 'admin')
        <!-- Data Barang Link -->
        <x-sidebar.sidebar-links route="barang.index" baseRoute="barang" icon="fas fa-fw fa-box" title="Data Barang" />

        <!-- Data User Link -->
        <x-sidebar.sidebar-links route="user.index" baseRoute="user" icon="fas fa-user" title="Users" />
    @endif

    <x-sidebar.sidebar-links route="transaksi.index" baseRoute="transaksi" icon="fas fa-fw fa-money-bill-wave" title="Transaction" />


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
