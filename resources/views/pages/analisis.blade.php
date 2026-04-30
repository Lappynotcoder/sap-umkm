@extends('layouts.dashboard')
@section('title', 'Analisis')

@push('styles')
<style>
    .chart-card {
        background: #fff; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 1.25rem;
    }
    .chart-title {
        font-size: 0.9rem; font-weight: 700; color: #333;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .chart-title i { color: #1a6b3a; }

    .stat-row {
        display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;
    }
    .stat-box {
        flex: 1; min-width: 140px;
        background: #fff; border-radius: 10px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        border-left: 4px solid #1a6b3a;
    }
    .stat-box.blue   { border-color: #0d6efd; }
    .stat-box.yellow { border-color: #f4a100; }
    .stat-box.red    { border-color: #dc3545; }
    .stat-box.purple { border-color: #6f42c1; }
    .stat-label { font-size: 0.75rem; color: #6c757d; font-weight: 600; text-transform: uppercase; }
    .stat-value { font-size: 1.2rem; font-weight: 700; color: #1a6b3a; }
    .stat-box.blue .stat-value   { color: #0d6efd; }
    .stat-box.yellow .stat-value { color: #f4a100; }
    .stat-box.red .stat-value    { color: #dc3545; }
    .stat-box.purple .stat-value { color: #6f42c1; }

    .insight-card {
        background: linear-gradient(135deg,#e8f5ee,#f8fdf9);
        border-radius: 10px; padding: 1rem 1.25rem;
        border-left: 4px solid #1a6b3a;
    }
    .insight-label { font-size: 0.75rem; color: #6c757d; font-weight: 600; text-transform: uppercase; }
    .insight-value { font-size: 1.05rem; font-weight: 700; color: #1a6b3a; }
    .insight-hint { font-size: 0.78rem; color: #adb5bd; margin-top: 0.25rem; }

    .umkm-table th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; }

    .empty-state {
        text-align: center; padding: 4rem 1rem; color: #adb5bd;
    }
    .empty-state i { font-size: 3.5rem; opacity: 0.25; }
</style>
@endpush

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-graph-up me-2" style="color:#1a6b3a"></i>Analisis
        </h4>
        <span class="text-muted small">Visualisasi & insight keuangan seluruh UMKM Anda</span>
    </div>
    <a href="{{ route('upload.form') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Input Transaksi Baru
    </a>
</div>

@if(!$hasData)
<div class="card card-metric">
    <div class="empty-state">
        <i class="bi bi-bar-chart d-block mb-3"></i>
        <h5 class="fw-bold text-muted">Belum Ada Data untuk Dianalisis</h5>
        <p class="text-muted">Input transaksi pertama Anda untuk melihat grafik dan insight di sini.</p>
        <a href="{{ route('upload.form') }}" class="btn btn-success mt-2">
            <i class="bi bi-plus-circle me-1"></i>Mulai Input
        </a>
    </div>
</div>
@else

{{-- ── RINGKASAN STATISTIK ── --}}
<div class="stat-row">
    <div class="stat-box">
        <div class="stat-label">Total Pemasukan</div>
        <div class="stat-value">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box blue">
        <div class="stat-label">Total HPP</div>
        <div class="stat-value">Rp {{ number_format($summary['total_hpp'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box yellow">
        <div class="stat-label">Biaya Operasional</div>
        <div class="stat-value">Rp {{ number_format($summary['total_operasional'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box {{ $summary['laba_bersih'] >= 0 ? '' : 'red' }}">
        <div class="stat-label">Laba Bersih</div>
        <div class="stat-value">Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-box purple">
        <div class="stat-label">Margin Bersih</div>
        <div class="stat-value">{{ $summary['margin_bersih'] }}%</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ── GRAFIK-GRAFIK ── --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}

{{-- Row 1: Tren Bulanan (Bar) + Komposisi Biaya (Doughnut) --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i>Pemasukan vs Pengeluaran Bulanan — {{ $tahun }}</div>
            <canvas id="chartBulanan" height="160"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-pie-chart-fill"></i>Komposisi Biaya</div>
            <canvas id="chartPie" height="160"></canvas>
        </div>
    </div>
</div>

{{-- Row 2: Tren Margin (Line) + Prediksi (Bar/Line) --}}
@if($trendData->count() > 1)
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-speedometer"></i>Tren Margin Profitabilitas</div>
            <canvas id="chartMargin" height="150"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-magic text-warning"></i>Forecasting (Regresi Linear)</div>
            <canvas id="chartPrediksi" height="150"></canvas>
            @if($prediksi)
                <div class="mt-3 small text-center text-muted">
                    Berdasarkan tren, proyeksi <strong>{{ $prediksi['label'] }}</strong>:<br>
                    Pemasukan: <span class="text-success fw-bold">Rp {{ number_format($prediksi['pemasukan'], 0, ',', '.') }}</span> |
                    Laba Bersih: <span class="fw-bold {{ $prediksi['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($prediksi['laba_bersih'], 0, ',', '.') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
{{-- ── INSIGHT BOXES ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Rasio HPP</div>
            <div class="insight-value">
                {{ $summary['total_pemasukan'] > 0 ? round(($summary['total_hpp'] / $summary['total_pemasukan']) * 100, 1) : 0 }}%
            </div>
            <div class="insight-hint">dari total pemasukan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Rasio Operasional</div>
            <div class="insight-value">
                {{ $summary['total_pemasukan'] > 0 ? round(($summary['total_operasional'] / $summary['total_pemasukan']) * 100, 1) : 0 }}%
            </div>
            <div class="insight-hint">dari total pemasukan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Margin Kotor</div>
            <div class="insight-value">{{ $summary['margin_kotor'] }}%</div>
            <div class="insight-hint">sebelum biaya operasional</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Efisiensi Laba</div>
            <div class="insight-value">
                Rp {{ $summary['total_pemasukan'] > 0 ? number_format(round($summary['laba_bersih'] / $summary['total_pemasukan'] * 100), 0, ',', '.') : 0 }}
            </div>
            <div class="insight-hint">laba per Rp 100 pemasukan</div>
        </div>
    </div>
</div>



@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
@if($hasData ?? false)
const fmtRp = v => 'Rp ' + Math.abs(v).toLocaleString('id-ID');
const fmtRpShort = v => {
    if (Math.abs(v) >= 1e6) return 'Rp ' + (v/1e6).toFixed(1) + 'jt';
    if (Math.abs(v) >= 1e3) return 'Rp ' + (v/1e3).toFixed(0) + 'rb';
    return 'Rp ' + v;
};

// ══════════════════════════════════════════════════════════════════════
// 1. BAR: Pemasukan vs Pengeluaran Bulanan
// ══════════════════════════════════════════════════════════════════════
const bulanan = {!! json_encode($chartBulanan) !!};
const lblBulan = bulanan.map(d => d.bulan);

new Chart(document.getElementById('chartBulanan'), {
    type: 'bar',
    data: {
        labels: lblBulan,
        datasets: [
            {
                label: 'Pemasukan',
                data: bulanan.map(d => d.pemasukan),
                backgroundColor: 'rgba(26,107,58,0.7)',
                borderRadius: 4,
            },
            {
                label: 'HPP',
                data: bulanan.map(d => d.hpp),
                backgroundColor: 'rgba(13,110,253,0.6)',
                borderRadius: 4,
            },
            {
                label: 'Operasional',
                data: bulanan.map(d => d.operasional),
                backgroundColor: 'rgba(244,161,0,0.6)',
                borderRadius: 4,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } }
        },
        scales: { y: { beginAtZero: true, ticks: { callback: fmtRpShort } } }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 2. DOUGHNUT: Komposisi Biaya
// ══════════════════════════════════════════════════════════════════════
new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: ['HPP', 'Operasional', 'Laba Bersih'],
        datasets: [{
            data: [
                {{ $summary['total_hpp'] }},
                {{ $summary['total_operasional'] }},
                Math.max(0, {{ $summary['laba_bersih'] }}),
            ],
            backgroundColor: ['#0d6efd','#f4a100','#1a6b3a'],
            hoverOffset: 8, borderWidth: 2,
        }]
    },
    options: {
        responsive: true, cutout: '55%',
        plugins: {
            tooltip: { callbacks: { label: ctx => ctx.label + ': ' + fmtRp(ctx.parsed) } },
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } }
        }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 3. TREN MARGIN
// ══════════════════════════════════════════════════════════════════════
@if($trendData->count() > 1)
const tLabels    = {!! json_encode($trendData->pluck('bulan')->map(function($d){ try{ return \Carbon\Carbon::parse($d)->format('d M Y'); } catch(\Exception $e){ return $d; } })) !!};
const tMK        = {!! json_encode($trendData->pluck('margin_kotor')) !!};
const tMB        = {!! json_encode($trendData->pluck('margin_bersih')) !!};

new Chart(document.getElementById('chartMargin'), {
    type: 'line',
    data: {
        labels: tLabels,
        datasets: [
            {
                label: 'Margin Kotor', data: tMK,
                borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.06)',
                fill: true, tension: 0.35, pointRadius: 4, pointBackgroundColor: '#0d6efd', borderWidth: 2,
            },
            {
                label: 'Margin Bersih', data: tMB,
                borderColor: '#1a6b3a', backgroundColor: 'rgba(26,107,58,.06)',
                fill: true, tension: 0.35, pointRadius: 4, pointBackgroundColor: '#1a6b3a', borderWidth: 2,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(1) + '%' } },
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
        },
        scales: { y: { ticks: { callback: v => v + '%' } } }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 4. PREDIKSI (FORECASTING)
// ══════════════════════════════════════════════════════════════════════
@if($prediksi)
const pLabels = [...tLabels, '{{ $prediksi["label"] }}'];
const actPemasukan = {!! json_encode($trendData->pluck('total_pemasukan')) !!};
const actLaba = {!! json_encode($trendData->pluck('laba_bersih')) !!};

const predPemasukan = [...actPemasukan, {{ $prediksi['pemasukan'] }}];
const predLaba = [...actLaba, {{ $prediksi['laba_bersih'] }}];

new Chart(document.getElementById('chartPrediksi'), {
    type: 'line',
    data: {
        labels: pLabels,
        datasets: [
            {
                label: 'Pemasukan (Aktual & Prediksi)',
                data: predPemasukan,
                borderColor: '#f4a100',
                backgroundColor: 'rgba(244,161,0,0.1)',
                fill: true,
                segment: { borderDash: ctx => ctx.p0DataIndex >= actPemasukan.length - 1 ? [6, 6] : undefined },
                tension: 0.3, pointRadius: 4, borderWidth: 2
            },
            {
                label: 'Laba Bersih (Aktual & Prediksi)',
                data: predLaba,
                borderColor: '#1a6b3a',
                backgroundColor: 'rgba(26,107,58,0.1)',
                fill: true,
                segment: { borderDash: ctx => ctx.p0DataIndex >= actLaba.length - 1 ? [6, 6] : undefined },
                tension: 0.3, pointRadius: 4, borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } },
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
        },
        scales: { y: { ticks: { callback: fmtRpShort } } }
    }
});
@endif

@endif



@endif
</script>
@endpush
