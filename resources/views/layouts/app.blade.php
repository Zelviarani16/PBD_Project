<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4e73df;
            --primary-dark: #2e59d9;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fc;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .sidebar-brand {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand i {
            font-size: 32px;
        }

        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            font-size: 22px;
        }

        .sidebar-user {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar-user-info h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .sidebar-user-info p {
            margin: 0;
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-title {
            padding: 15px 20px 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            opacity: 0.6;
            letter-spacing: 1px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 25px;
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left: 4px solid white;
        }

        .sidebar-menu a i {
            font-size: 18px;
            width: 20px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar-left h5 {
            margin: 0;
            color: #5a5c69;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        /* .topbar-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--secondary-color);
        } */

        /* .topbar-icon:hover {
            background: var(--primary-color);
            color: white;
        } */

        .content-wrapper {
            padding: 30px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h3 {
            color: #5a5c69;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: var(--secondary-color);
            margin: 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #f8f9fc;
            padding: 20px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-card {
            border-left: 4px solid var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.info {
            border-left-color: var(--info-color);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: #f8f9fc;
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e3e6f0;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: #f8f9fc;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- SIDEBARRRRR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class='bx bxs-dashboard'></i>
            <h4>SYNVENT</h4>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <i class='bx bxs-user'></i>
            </div>
            <div class="sidebar-user-info">
                <!-- Setelah login, ambil Username -->
                <h6>{{ session('username') }}</h6>
                <p>Super Admin</p>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-title">Main Menu</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard'></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <i class='bx bx-box'></i>
                <span>Data Barang</span>
            </a>
            <a href="{{ route('satuan.index') }}" class="{{ request()->routeIs('satuan.*') ? 'active' : '' }}">
                <i class='bx bx-collection'></i>
                <span>Data Satuan</span>
            </a>
             <a href="{{ route('vendor.index') }}" class="{{ request()->routeIs('vendor.*') ? 'active' : '' }}">
        <i class='bx bx-store'></i>
        <span>Data Vendor</span>
        </a>
        <a href="{{ route('role.index') }}" class="{{ request()->routeIs('role.*') ? 'active' : '' }}">
            <i class='bx bx-user-check'></i>
            <span>Data Role</span>
        </a>
        <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
            <i class='bx bx-user'></i>
            <span>Data User</span>
        </a>
        <a href="{{ route('margin.index') }}" class="{{ request()->routeIs('margin_penjualan.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Margin Penjualan</span>
        </a>
        <a href="{{ route('pengadaan.index') }}" class="{{ request()->routeIs('pengadaan.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Pengadaan</span>
        </a>
        <a href="{{ route('penerimaan.index') }}" class="{{ request()->routeIs('penerimaan.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Penerimaan</span>
        </a>
        <a href="{{ route('penjualan.index') }}" class="{{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Penjualan</span>
        </a>
        <a href="{{ route('retur.index') }}" class="{{ request()->routeIs('retur_barang.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Retur Barang</span>
        </a>
        <a href="{{ route('kartu.index') }}" class="{{ request()->routeIs('kartu_stok.*') ? 'active' : '' }}">
            <i class='bx bx-dollar'></i>
            <span>Kartu Stok</span>
        </a>
        
            <div class="menu-title">System</div>
            <a href="/logout" onclick="return confirm('Yakin ingin logout?')">
                <i class='bx bx-log-out'></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="topbar-right">
                <div class="topbar-icon">
                    <!-- <i class='bx bx-bell'></i> -->
                </div>
                <div class="topbar-icon">
                    <!-- <i class='bx bx-cog'></i> -->
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class='bx bx-check-circle'></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle'></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    @stack('scripts')
</body>
</html>