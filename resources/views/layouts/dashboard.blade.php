<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAP-UMKM — @yield('title', 'Sistem Analisis Profit UMKM')</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #1A374D;
            --sidebar-hover: #406882;
            --sidebar-active: #406882;
            --topbar-bg: #1A374D;
            --accent: #F2AB39;
            --accent-green: #34A853;
            --accent-red: #EF4444;
            --body-bg: #F1F5F9;
            --sidebar-w: 250px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', 'Segoe UI', sans-serif; background: var(--body-bg); }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w); height: calc(100vh - 60px);
            background: var(--sidebar-bg);
            position: fixed; left: 0; top: 60px; z-index: 1000;
            display: flex; flex-direction: column;
            transition: transform 0.3s;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-nav { padding: 1rem 0.7rem; flex: 1; }

        .sidebar-link {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.65rem 1rem; border-radius: 8px;
            color: rgba(255,255,255,0.5);
            text-decoration: none; font-size: 0.85rem; font-weight: 500;
            transition: all 0.2s; margin-bottom: 0.15rem;
        }
        .sidebar-link:hover { background: var(--sidebar-hover); color: rgba(255,255,255,0.85); }
        .sidebar-link.active { background: var(--sidebar-active); color: #fff; font-weight: 600; }
        .sidebar-link i { font-size: 1.05rem; width: 20px; text-align: center; }

        /* ── Main ── */
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; padding-top: 60px; }

        /* ── Top bar ── */
        .topbar {
            background: var(--topbar-bg);
            height: 60px;
            padding: 0 1.75rem;
            position: fixed; top: 0; left: 0; width: 100%; z-index: 1050;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .topbar-brand {
            font-size: 1.1rem; font-weight: 700; color: #fff; text-decoration: none; letter-spacing: 0.5px;
        }
        .topbar-brand:hover { color: #fff; }
        
        .topbar .dropdown-toggle::after { display: none; }
        .topbar .user-name { color: rgba(255,255,255,0.9); font-size: 0.9rem; font-weight: 500; }

        /* ── Content ── */
        .page-content { padding: 1.5rem 1.75rem; }

        /* ── Cards ── */
        .card-metric, .chart-card {
            border: 1.5px solid #2d3748;
            border-radius: 12px;
            box-shadow: none;
            background: #fff;
        }

        .badge-profit-pos { background: #dcfce7; color: #166534; }
        .badge-profit-neg { background: #fee2e2; color: #991b1b; }

        /* ── Table & Buttons ── */
        .table thead th { background-color: #406882 !important; color: #fff !important; border-bottom: 2px solid #2d3748 !important; font-weight: 600; }
        .table tbody tr { border-bottom: 1px solid #e2e8f0; }
        
        .btn-pill { border-radius: 50rem !important; padding: 0.5rem 1.5rem; font-weight: 600; transition: all 0.3s ease; }
        .btn-pill:hover { filter: brightness(0.9); }

        /* ── Responsive ── */
        .sidebar-toggler { display: none; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggler { display: inline-flex; }
            .page-content { padding: 1rem 0.75rem; }
        }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; }
        .sidebar-overlay.show { display: block; }

        /* ── Mobile Card Layout ── */
        @media (max-width: 768px) {
            .mobile-cards thead { display: none; }
            .mobile-cards tfoot { display: none; }

            .mobile-cards tbody tr {
                display: block;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                padding: 0.85rem 1rem;
                margin-bottom: 0.75rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                transition: box-shadow 0.2s;
            }
            .mobile-cards tbody tr:hover {
                box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            }

            .mobile-cards tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem 0 !important;
                border: none !important;
                font-size: 0.88rem;
                text-align: right !important;
            }

            .mobile-cards tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #475569;
                font-size: 0.8rem;
                text-align: left;
                flex-shrink: 0;
                margin-right: 1rem;
            }

            .mobile-cards tbody td:first-child {
                padding-top: 0.15rem !important;
            }
            .mobile-cards tbody td:last-child {
                padding-bottom: 0.15rem !important;
            }

            /* Hide row number column on mobile */
            .mobile-cards tbody td.row-num-cell {
                display: none;
            }

            /* Input table card mode */
            .mobile-cards.input-table {
                border-spacing: 0;
            }
            .mobile-cards.input-table tbody tr {
                border-left: 4px solid var(--accent, #F2AB39);
            }
            .mobile-cards.input-table tbody td {
                display: block;
                text-align: left !important;
                padding: 0.3rem 0 !important;
            }
            .mobile-cards.input-table tbody td::before {
                display: block;
                margin-bottom: 0.25rem;
                color: #64748b;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }
            .mobile-cards.input-table tbody td .form-control,
            .mobile-cards.input-table tbody td .form-select {
                width: 100%;
            }
            .mobile-cards.input-table .row-num {
                display: none;
            }
            .mobile-cards.input-table tbody td:last-child {
                text-align: center !important;
                padding-top: 0.5rem !important;
                border-top: 1px dashed #e2e8f0;
                margin-top: 0.25rem;
            }

            /* Summary table mobile */
            .summary-table.mobile-cards tbody tr {
                border-left: 4px solid #1a6b3a;
            }
            .summary-table.mobile-cards tbody td,
            .summary-table.mobile-cards tbody th {
                display: block;
                width: 100% !important;
                text-align: left !important;
            }
            .summary-table.mobile-cards tbody th {
                font-size: 0.78rem;
                color: #64748b;
                padding-bottom: 0 !important;
                background: transparent !important;
            }
            .summary-table.mobile-cards tbody td {
                font-size: 0.95rem;
                padding-top: 0 !important;
            }
        }

        @media print {
            .sidebar, .topbar, .d-print-none { display: none !important; }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<aside class="sidebar" id="sidebar">
    <!-- removed sidebar brand -->
    <nav class="sidebar-nav">
        <a href="{{ route('riwayat') }}"
           class="sidebar-link {{ request()->routeIs('riwayat') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('upload.form') }}"
           class="sidebar-link {{ request()->routeIs('upload.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Keuangan
        </a>
        <a href="{{ route('produk.index') }}"
           class="sidebar-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Produk & Stok
        </a>
        <a href="{{ route('laporan') }}"
           class="sidebar-link {{ request()->routeIs('laporan') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
        <a href="{{ route('analisis') }}"
           class="sidebar-link {{ request()->routeIs('analisis') || request()->routeIs('dashboard.show') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Analisis
        </a>
        <a href="{{ route('history') }}"
           class="sidebar-link {{ request()->routeIs('history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>
        <a href="{{ route('profile.edit') }}"
           class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
    </nav>
</aside>

{{-- MAIN --}}
<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm sidebar-toggler text-white" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a href="{{ route('riwayat') }}" class="topbar-brand">SAP - UMKM</a>
        </div>
        
        <div class="dropdown">
            <div class="d-flex align-items-center gap-2 dropdown-toggle text-white" role="button" data-bs-toggle="dropdown" style="cursor:pointer">
                <span class="user-name d-none d-sm-inline">{{ explode(' ', Auth::user()->name ?? 'User')[0] }}</span>
                <i class="bi bi-person-circle fs-5"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    {{-- FLASH --}}
    @if(session('success') || session('error') || $errors->any())
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    @endif

    <main class="page-content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
@stack('scripts')
</body>
</html>
