@extends('layouts.app')
@section('title', 'Tentang SAP-UMKM')

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="p-5 mb-4 rounded-4 text-white" style="background: linear-gradient(135deg, #1a6b3a 60%, #2e9e5b);">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="bi bi-info-circle-fill me-2" style="color:#f4a100"></i>
                Tentang SAP-UMKM
            </h2>
            <p class="lead opacity-90 mb-0">
                Sistem Analisis Profit UMKM — Platform digital untuk membantu pelaku UMKM
                Cilacap menganalisis profitabilitas bisnis secara otomatis dari file Excel.
            </p>
        </div>
        <div class="col-md-4 text-center d-none d-md-block">
            <i class="bi bi-graph-up-arrow" style="font-size:6rem;opacity:.3"></i>
        </div>
    </div>
</div>

{{-- ── LATAR BELAKANG ───────────────────────────────────────────────────── --}}
<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card card-metric p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb-fill me-2 text-warning"></i>Latar Belakang</h5>
            <p class="text-muted mb-2">
                Kabupaten Cilacap memiliki ekosistem ekonomi lokal yang sangat dinamis, didukung oleh
                sektor perikanan pesisir pantai, pariwisata Teluk Penyu, industri pengolahan makanan khas,
                hingga kerajinan. Namun, banyak pelaku UMKM masih terhambat oleh minimnya literasi manajemen
                keuangan, khususnya dalam melakukan analisis profitabilitas.
            </p>
            <p class="text-muted mb-0">
                SAP-UMKM hadir sebagai solusi digital yang memanfaatkan teknologi <strong>Laravel 12</strong>
                sebagai backend web dan mesin komputasi analitik dengan PHP murni, serta
                <strong>Chart.js</strong> untuk visualisasi interaktif — sehingga analisis keuangan yang
                kompleks bisa dilakukan secara instan hanya dengan menginput data transaksi manual secara langsung.
            </p>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-metric p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-gear-fill me-2" style="color:#1a6b3a"></i>Arsitektur Sistem</h5>
            <div class="d-flex flex-column gap-2">
                @php
                $arch = [
                    ['icon'=>'bi-browser-chrome','label'=>'Frontend','desc'=>'Blade Template + Chart.js','color'=>'#0d6efd'],
                    ['icon'=>'bi-server','label'=>'Backend','desc'=>'Laravel 12 (PHP 8.2)','color'=>'#1a6b3a'],
                    ['icon'=>'bi-calculator','label'=>'Analitik','desc'=>'PHP Native Processing','color'=>'#f4a100'],
                    ['icon'=>'bi-database-fill','label'=>'Database','desc'=>'MySQL 8.0','color'=>'#dc3545'],
                ];
                @endphp
                @foreach($arch as $a)
                <div class="d-flex align-items-center gap-3 p-2 rounded-2" style="background:#f8fdf9">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;background:{{ $a['color'] }}22;flex-shrink:0">
                        <i class="bi {{ $a['icon'] }}" style="color:{{ $a['color'] }}"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">{{ $a['label'] }}</div>
                        <div class="text-muted" style="font-size:.8rem">{{ $a['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ── FITUR UTAMA ──────────────────────────────────────────────────────── --}}
<div class="card card-metric p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-stars me-2 text-warning"></i>Fitur Utama</h5>
    <div class="row g-3">
        @php
        $fitur = [
            ['icon'=>'bi-file-earmark-spreadsheet','judul'=>'Template Excel Standar','desc'=>'Download template Excel berformat standar (kategori Pemasukan/HPP/Operasional) yang siap diisi secara offline.','color'=>'#1a6b3a'],
            ['icon'=>'bi-shield-check','judul'=>'Validasi Upload Aman','desc'=>'Sistem memvalidasi format file (.xlsx/.csv), ukuran maks 2 MB, dan struktur kolom sebelum diproses.','color'=>'#0d6efd'],
            ['icon'=>'bi-cpu','judul'=>'Komputasi Python Otomatis','desc'=>'Mesin Python menghitung Laba Kotor, Laba Bersih, Margin, dan BEP secara instan dari file yang diunggah.','color'=>'#f4a100'],
            ['icon'=>'bi-bar-chart-line','judul'=>'Dashboard Visualisasi','desc'=>'Hasil analisis ditampilkan dalam grafik batang, diagram donat, dan tren multi-bulan berbasis Chart.js.','color'=>'#2e9e5b'],
            ['icon'=>'bi-clock-history','judul'=>'Arsip Riwayat Analisis','desc'=>'Seluruh hasil analisis tersimpan di database MySQL dan bisa diakses kapan saja untuk monitoring tren bisnis.','color'=>'#6f42c1'],
            ['icon'=>'bi-bullseye','judul'=>'Analisis Break Even Point','desc'=>'Sistem menghitung titik impas (BEP) dan memberi notifikasi apakah pemasukan bulan ini sudah melampaui BEP.','color'=>'#dc3545'],
        ];
        @endphp
        @foreach($fitur as $f)
        <div class="col-sm-6 col-md-4">
            <div class="d-flex gap-3 align-items-start">
                <div class="rounded-circle d-flex align-items-center justify-content-center mt-1"
                     style="width:38px;height:38px;background:{{ $f['color'] }}18;flex-shrink:0">
                    <i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }}"></i>
                </div>
                <div>
                    <div class="fw-semibold small">{{ $f['judul'] }}</div>
                    <div class="text-muted" style="font-size:.82rem">{{ $f['desc'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── SDGs ─────────────────────────────────────────────────────────────── --}}
<div class="card card-metric p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-globe-americas me-2 text-success"></i>Kontribusi pada SDGs</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-3 rounded-3 h-100" style="background:#fff0f3;border-left:4px solid #a21942">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge sdg-8 px-2 py-1">SDGs 8</span>
                    <strong class="small">Pekerjaan Layak & Pertumbuhan Ekonomi</strong>
                </div>
                <p class="text-muted small mb-0">
                    SAP-UMKM mendorong formalisasi dan pertumbuhan usaha mikro melalui peningkatan
                    produktivitas berbasis inovasi teknologi. Dengan analisis margin yang akurat, UMKM
                    dapat mengambil keputusan bisnis berbasis data untuk mencegah kebangkrutan dan
                    menciptakan pertumbuhan ekonomi lokal yang berkelanjutan.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 rounded-3 h-100" style="background:#fff4ee;border-left:4px solid #fd6925">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge sdg-9 px-2 py-1">SDGs 9</span>
                    <strong class="small">Industri, Inovasi & Infrastruktur</strong>
                </div>
                <p class="text-muted small mb-0">
                    Platform ini meningkatkan akses industri skala kecil terhadap layanan teknologi
                    informasi dan finansial yang inklusif. Inovasi integrasi Excel + Python + Web
                    mendemokratisasi perangkat analitik yang biasanya hanya dimiliki korporasi besar,
                    agar bisa diakses usaha mikro di tingkat daerah.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ── TIM PENGEMBANG ───────────────────────────────────────────────────── --}}
<div class="card card-metric p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-people-fill me-2" style="color:#1a6b3a"></i>Tim Pengembang</h5>
    <div class="row g-3">
        @php
        $tim = [
            ['nama'=>'Firly Nurrohman','nim'=>'250115042','peran'=>'Project Lead & Backend Developer'],
            ['nama'=>'Ade Ariyansyah','nim'=>'— (NIM)','peran'=>'Frontend & UI/UX Developer'],
            ['nama'=>'Bintang Fajar Joyla','nim'=>'— (NIM)','peran'=>'Python Data Engineer'],
        ];
        @endphp
        @foreach($tim as $t)
        <div class="col-md-4">
            <div class="text-center p-3 rounded-3" style="background:#f8fdf9">
                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#1a6b3a22">
                    <i class="bi bi-person-fill fs-4" style="color:#1a6b3a"></i>
                </div>
                <div class="fw-semibold">{{ $t['nama'] }}</div>
                <div class="text-muted small">{{ $t['nim'] }}</div>
                <span class="badge mt-1" style="background:#e8f5ee;color:#1a6b3a">{{ $t['peran'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-3 text-muted small">
        <i class="bi bi-building me-1"></i>Politeknik Negeri Cilacap &nbsp;|&nbsp; 2026
    </div>
</div>

{{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
<div class="text-center py-3">
    <a href="{{ route('upload.form') }}" class="btn btn-success fw-semibold px-4">
        <i class="bi bi-cloud-upload me-2"></i>Mulai Analisis
    </a>
</div>

@endsection
