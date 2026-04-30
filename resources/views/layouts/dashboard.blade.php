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
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --sidebar-active: #1a6b3a;
            --topbar-bg: #111827;
            --accent: #22c55e;
            --accent-orange: #f59e0b;
            --body-bg: #f0f2f5;
            --sidebar-w: 250px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', 'Segoe UI', sans-serif; background: var(--body-bg); }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed; left: 0; top: 0; z-index: 1000;
            display: flex; flex-direction: column;
            transition: transform 0.3s;
        }

        .sidebar-brand {
            padding: 1.2rem 1.25rem;
            display: flex; align-items: center; gap: 0.7rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: var(--accent); border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-brand-text { font-size: 1.05rem; font-weight: 700; color: #fff; }
        .sidebar-brand-text span { color: rgba(255,255,255,0.45); font-weight: 400; }

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
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; }

        /* ── Top bar ── */
        .topbar {
            background: var(--topbar-bg);
            padding: 0.6rem 1.75rem;
            display: flex; justify-content: flex-end; align-items: center;
            gap: 1rem;
        }
        .topbar-bell { color: rgba(255,255,255,0.5); font-size: 1.15rem; cursor: pointer; transition: color 0.2s; }
        .topbar-bell:hover { color: var(--accent); }
        .topbar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--accent); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.75rem;
        }
        .topbar .dropdown-toggle::after { display: none; }
        .topbar .user-name { color: rgba(255,255,255,0.8); font-size: 0.82rem; font-weight: 500; }

        /* ── Content ── */
        .page-content { padding: 1.5rem 1.75rem; }

        /* ── Cards ── */
        .card-metric {
            border: none; border-radius: 10px;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
            background: #fff;
        }

        .badge-profit-pos { background: #dcfce7; color: #166534; }
        .badge-profit-neg { background: #fee2e2; color: #991b1b; }

        /* ── Responsive ── */
        .sidebar-toggler { display: none; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggler { display: inline-flex; }
        }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; }
        .sidebar-overlay.show { display: block; }

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
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-bar-chart-line-fill" style="color:#fff;font-size:1rem"></i>
        </div>
        <div class="sidebar-brand-text">SAP<span>-UMKM</span></div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('riwayat') }}"
           class="sidebar-link {{ request()->routeIs('riwayat') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('upload.form') }}"
           class="sidebar-link {{ request()->routeIs('upload.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Keuangan
        </a>
        <a href="{{ route('laporan') }}"
           class="sidebar-link {{ request()->routeIs('laporan') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
        <a href="{{ route('analisis') }}"
           class="sidebar-link {{ request()->routeIs('analisis') || request()->routeIs('dashboard.show') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Analisis
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
        <button class="btn btn-sm sidebar-toggler me-auto text-white" onclick="toggleSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-bell"><i class="bi bi-bell"></i></div>
        <div class="dropdown">
            <div class="d-flex align-items-center gap-2 dropdown-toggle" role="button" data-bs-toggle="dropdown">
                <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                <span class="user-name d-none d-sm-inline">{{ Auth::user()->name ?? 'User' }}</span>
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
