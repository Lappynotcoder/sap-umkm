@extends('layouts.dashboard')
@section('title', 'Dashboard')

@push('styles')
<style>
    .metric-card {
        border-left: 4px solid; border-radius: 10px;
        background: #fff; padding: 1.1rem 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        transition: transform .2s;
    }
    .metric-card:hover { transform: translateY(-2px); }
    .mc-hijau  { border-color: #1a6b3a; }
    .mc-biru   { border-color: #0d6efd; }
    .mc-kuning { border-color: #f4a100; }
    .mc-merah  { border-color: #dc3545; }
    .mc-ungu   { border-color: #6f42c1; }

    .metric-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
    }
    .metric-value { font-size: 1.15rem; font-weight: 700; }
    .metric-label { font-size: 0.78rem; color: #6c757d; }

    .chart-card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 1.25rem;
    }

    .section-title {
        font-size: 0.95rem; font-weight: 700; color: #333;
        margin-bottom: 1rem;
    }
    .section-title i { color: #1a6b3a; }

    .empty-state {
        text-align: center; padding: 3rem 1rem; color: #adb5bd;
    }
    .empty-state i { font-size: 3rem; opacity: 0.3; }
</style>
@endpush

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-speedometer2 me-2" style="color:#1a6b3a"></i>Dashboard
        </h4>
        <span class="text-muted small">Ringkasan analisis keuangan UMKM Anda</span>
    </div>
    <a href="{{ route('upload.form') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Input Transaksi Baru
    </a>
</div>

@if($summary['jumlah_laporan'] > 0)

{{-- ── KARTU METRIK RINGKASAN ── --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Pemasukan',   'value'=> $summary['total_pemasukan'],   'icon'=>'bi-cash-coin',      'kelas'=>'mc-hijau',  'color'=>'#1a6b3a'],
        ['label'=>'Total HPP',         'value'=> $summary['total_hpp'],         'icon'=>'bi-box-seam',       'kelas'=>'mc-biru',   'color'=>'#0d6efd'],
        ['label'=>'Biaya Operasional', 'value'=> $summary['total_operasional'], 'icon'=>'bi-wallet2',        'kelas'=>'mc-kuning', 'color'=>'#f4a100'],
        ['label'=>'Laba Bersih',       'value'=> $summary['laba_bersih'],       'icon'=>'bi-trophy-fill',    'kelas'=> $summary['laba_bersih'] >= 0 ? 'mc-hijau' : 'mc-merah', 'color'=> $summary['laba_bersih'] >= 0 ? '#1a6b3a' : '#dc3545'],
        ['label'=>'Margin Bersih',     'value'=> null,                          'icon'=>'bi-percent',        'kelas'=> $summary['margin_bersih'] >= 0 ? 'mc-hijau' : 'mc-merah', 'color'=> $summary['margin_bersih'] >= 0 ? '#1a6b3a' : '#dc3545', 'persen'=>$summary['margin_bersih']],
        ['label'=>'Total Laporan',     'value'=> null,                          'icon'=>'bi-journal-check',  'kelas'=>'mc-ungu',   'color'=>'#6f42c1', 'count'=>$summary['jumlah_laporan']],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="metric-card {{ $c['kelas'] }} h-100">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="metric-icon" style="background:{{ $c['color'] }}15">
                    <i class="bi {{ $c['icon'] }}" style="color:{{ $c['color'] }}"></i>
                </div>
            </div>
            <div class="metric-value" style="color:{{ $c['color'] }}">
                @if(isset($c['persen']))
                    {{ $c['persen'] }}%
                @elseif(isset($c['count']))
                    {{ $c['count'] }}
                @else
                    Rp {{ number_format($c['value'], 0, ',', '.') }}
                @endif
            </div>
            <div class="metric-label">{{ $c['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── GRAFIK BAR: Pemasukan vs Pengeluaran Bulanan ── --}}
<div class="chart-card mb-4">
    <div class="section-title">
        <i class="bi bi-bar-chart-fill me-2"></i>Pemasukan vs Pengeluaran — Tahun {{ $tahun }}
    </div>
    <canvas id="chartBulanan" height="100"></canvas>
</div>

@endif

{{-- ── TABEL RIWAYAT (RINGKAS) ── --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="section-title mb-0"><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</div>
</div>

@if($riwayat->count() > 0)
<div class="card card-metric p-3 mb-3">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th class="text-end">Laba Bersih</th>
                    <th>Margin</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($riwayat->take(5) as $r)
            <tr>
                <td class="small text-muted">
                    @php
                        try { $tgl = \Carbon\Carbon::parse($r->bulan)->translatedFormat('d M Y'); }
                        catch(\Exception $e) { $tgl = $r->bulan; }
                    @endphp
                    {{ $tgl }}
                </td>
                <td class="text-end {{ $r->laba_bersih >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    Rp {{ number_format($r->laba_bersih,0,',','.') }}
                </td>
                <td>
                    <span class="badge {{ $r->margin_bersih >= 0 ? 'badge-profit-pos' : 'badge-profit-neg' }}">
                        {{ $r->margin_bersih }}%
                    </span>
                </td>
                <td>
                    <a href="{{ route('dashboard.show', $r->id) }}" class="btn btn-sm btn-outline-success" title="Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($riwayat->total() > 5)
    <div class="text-center mt-3">
        <a href="{{ route('riwayat', ['page' => 1]) }}" class="text-muted small text-decoration-none">
            Lihat semua {{ $riwayat->total() }} transaksi <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    @endif
</div>
@else
<div class="card card-metric">
    <div class="empty-state">
        <i class="bi bi-inbox d-block mb-3"></i>
        <p>Belum ada data transaksi.</p>
        <a href="{{ route('upload.form') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Input Transaksi Pertama
        </a>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
@if($summary['jumlah_laporan'] > 0)
const chartData = {!! json_encode($chartBulanan) !!};
const labels = chartData.map(d => d.bulan);
const pemasukan = chartData.map(d => d.pemasukan);
const pengeluaran = chartData.map(d => d.pengeluaran);

const fmtRp = v => 'Rp ' + Math.abs(v).toLocaleString('id-ID');

new Chart(document.getElementById('chartBulanan'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: pemasukan,
                backgroundColor: 'rgba(26,107,58,0.75)',
                borderRadius: 4,
            },
            {
                label: 'Pengeluaran',
                data: pengeluaran,
                backgroundColor: 'rgba(220,53,69,0.6)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 12 } } },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => {
                        if (v >= 1e6) return 'Rp ' + (v/1e6).toFixed(1) + 'jt';
                        if (v >= 1e3) return 'Rp ' + (v/1e3).toFixed(0) + 'rb';
                        return 'Rp ' + v;
                    }
                }
            }
        }
    }
});
@endif
</script>
@endpush
