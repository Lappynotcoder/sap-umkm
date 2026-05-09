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

@if($trendData->count() > 0)
<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-range me-2"></i>Analisis Tahun <span id="currentYearDisplay">{{ $tahun }}</span></h5>
    <div>
        <button class="btn btn-sm btn-outline-secondary me-1" id="btnPrevYear" title="Tahun Sebelumnya"><i class="bi bi-chevron-left"></i></button>
        <button class="btn btn-sm btn-outline-secondary" id="btnNextYear" title="Tahun Berikutnya"><i class="bi bi-chevron-right"></i></button>
    </div>
</div>

{{-- Row 1: Tren Bulanan (Bar) + Komposisi Biaya (Doughnut) --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i>Pemasukan vs Pengeluaran Bulanan</div>
            <div class="table-responsive"><div style="min-width: 600px;">
                <canvas id="chartBulanan" height="160"></canvas>
            </div></div>
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
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-speedometer"></i>Perkembangan Keuntungan</div>
            <div class="table-responsive"><div style="min-width: 500px;">
                <canvas id="chartMargin" height="150"></canvas>
            </div></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-magic text-warning"></i>Perkiraan Penjualan</div>
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
// INISIALISASI DATA & CHART GLOBAL
// ══════════════════════════════════════════════════════════════════════
@if($trendData->count() > 0)
// Raw data untuk komputasi chart Bar & Pie
const master_raw = {!! json_encode($trendData->map(function($i) { 
    return ['thn' => $i->thn, 'bln' => $i->bln, 'pem' => $i->total_pemasukan, 'hpp' => $i->total_hpp, 'ops' => $i->total_operasional, 'laba' => $i->laba_bersih]; 
})) !!};

// Master Data Margin
const master_tLabels = {!! json_encode($trendData->pluck('bulan')->map(function($d){ try{ return \Carbon\Carbon::parse($d)->format('M Y'); } catch(\Exception $e){ return $d; } })) !!};
const master_tMK = {!! json_encode($trendData->pluck('margin_kotor')) !!};
const master_tMB = {!! json_encode($trendData->pluck('margin_bersih')) !!};

// Master Data Prediksi
const master_trainLabels = {!! json_encode($trainingData->pluck('bulan')->map(function($d){ try{ return \Carbon\Carbon::parse($d)->format('M Y'); } catch(\Exception $e){ return $d; } })) !!};
const master_pLabels = [...master_trainLabels, '{{ $prediksi["label"] ?? "" }}'];
const master_actPemasukan = {!! json_encode($trainingData->pluck('total_pemasukan')) !!};
const master_actLaba = {!! json_encode($trainingData->pluck('laba_bersih')) !!};
const master_predPemasukan = [...master_actPemasukan, {{ $prediksi['pemasukan'] ?? 0 }}];
const master_predLaba = [...master_actLaba, {{ $prediksi['laba_bersih'] ?? 0 }}];

const availableYearsArr = [...new Set(master_raw.map(r => r.thn))];
const minYear = availableYearsArr.length > 0 ? Math.min(...availableYearsArr) : {{ date('Y') }};
const maxYear = {{ date('Y') }};

let activeYear = {{ $tahun }};

// --- Inisialisasi Chart ---
const lblBulanAll = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

const chartBulanan = new Chart(document.getElementById('chartBulanan'), {
    type: 'bar',
    data: {
        labels: lblBulanAll,
        datasets: [
            { label: 'Pemasukan', data: [], backgroundColor: '#34A853', borderRadius: 4 },
            { label: 'HPP', data: [], backgroundColor: '#406882', borderRadius: 4 },
            { label: 'Operasional', data: [], backgroundColor: '#F2AB39', borderRadius: 4 }
        ]
    },
    options: {
        responsive: true, interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }, tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } } },
        scales: { 
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { callback: fmtRpShort } } 
        }
    }
});

const chartPie = new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: ['HPP', 'Operasional', 'Laba Bersih'],
        datasets: [{ data: [], backgroundColor: ['#406882','#F2AB39','#34A853'], hoverOffset: 8, borderWidth: 2 }]
    },
    options: {
        responsive: true, cutout: '65%',
        plugins: { tooltip: { callbacks: { label: ctx => ctx.label + ': ' + fmtRp(ctx.parsed) } }, legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } } }
    }
});

const chartMargin = new Chart(document.getElementById('chartMargin'), {
    type: 'line',
    data: { labels: [], datasets: [
        { label: 'Margin Kotor', data: [], borderColor: '#F2AB39', backgroundColor: 'rgba(242,171,57,.06)', fill: true, tension: 0.35, pointRadius: 4, pointBackgroundColor: '#F2AB39', borderWidth: 2 },
        { label: 'Margin Bersih', data: [], borderColor: '#34A853', backgroundColor: 'rgba(52,168,83,.06)', fill: true, tension: 0.35, pointRadius: 4, pointBackgroundColor: '#34A853', borderWidth: 2 }
    ]},
    options: {
        responsive: true, interaction: { mode: 'index', intersect: false },
        plugins: { tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(1) + '%' } }, legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
        scales: { 
            x: { grid: { display: false } },
            y: { ticks: { callback: v => v + '%' } } 
        }
    }
});

const chartPrediksi = new Chart(document.getElementById('chartPrediksi'), {
    type: 'line',
    data: { labels: [], datasets: [
        { label: 'Pemasukan (Aktual & Prediksi)', data: [], borderColor: '#406882', backgroundColor: 'rgba(64,104,130,0.1)', fill: true, tension: 0.3, pointRadius: 4, borderWidth: 2 },
        { label: 'Laba Bersih (Aktual & Prediksi)', data: [], borderColor: '#34A853', backgroundColor: 'rgba(52,168,83,0.1)', fill: true, tension: 0.3, pointRadius: 4, borderWidth: 2 }
    ]},
    options: {
        responsive: true, interaction: { mode: 'index', intersect: false },
        plugins: { tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } }, legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
        scales: { 
            x: { grid: { display: false } },
            y: { ticks: { callback: fmtRpShort } } 
        }
    }
});

function updateDynamicCharts(year) {
    document.getElementById('currentYearDisplay').innerText = year;
    
    document.getElementById('btnPrevYear').disabled = (year <= minYear);
    document.getElementById('btnNextYear').disabled = (year >= maxYear);

    // 1. Filter Data Bar Bulanan & Pie
    let sumHPP = 0, sumOps = 0, sumLaba = 0;
    let barPem = Array(12).fill(0), barHPP = Array(12).fill(0), barOps = Array(12).fill(0);

    master_raw.forEach(row => {
        if (row.thn == year) {
            sumHPP += parseFloat(row.hpp) || 0;
            sumOps += parseFloat(row.ops) || 0;
            sumLaba += parseFloat(row.laba) || 0;
            let mIdx = row.bln - 1; // 0-11
            barPem[mIdx] = parseFloat(row.pem) || 0;
            barHPP[mIdx] = parseFloat(row.hpp) || 0;
            barOps[mIdx] = parseFloat(row.ops) || 0;
        }
    });

    chartBulanan.data.datasets[0].data = barPem;
    chartBulanan.data.datasets[1].data = barHPP;
    chartBulanan.data.datasets[2].data = barOps;
    chartBulanan.update();

    chartPie.data.datasets[0].data = [sumHPP, sumOps, Math.max(0, sumLaba)];
    chartPie.update();

    // 2. Filter Margin Data
    const mIdx = master_tLabels.map((l, i) => l.includes(year.toString()) ? i : -1).filter(i => i !== -1);
    chartMargin.data.labels = mIdx.map(i => master_tLabels[i]);
    chartMargin.data.datasets[0].data = mIdx.map(i => master_tMK[i]);
    chartMargin.data.datasets[1].data = mIdx.map(i => master_tMB[i]);
    chartMargin.update();

    // Filter Prediksi Data
    const pIdx = master_pLabels.map((l, i) => l.includes(year.toString()) && l !== "" ? i : -1).filter(i => i !== -1);
    chartPrediksi.data.labels = pIdx.map(i => master_pLabels[i]);
    chartPrediksi.data.datasets[0].data = pIdx.map(i => master_predPemasukan[i]);
    chartPrediksi.data.datasets[1].data = pIdx.map(i => master_predLaba[i]);

    // Segment Logic: Hanya putus-putus pada titik terakhir (karena titik terakhir master adalah prediksi)
    // Jika prediksi point masuk dalam tahun ini, index akhirnya harus putus-putus
    const actCountInSlice = pIdx.filter(i => i < master_actPemasukan.length).length;
    chartPrediksi.data.datasets[0].segment = { borderDash: ctx => ctx.p0DataIndex >= actCountInSlice - 1 ? [6, 6] : undefined };
    chartPrediksi.data.datasets[1].segment = { borderDash: ctx => ctx.p0DataIndex >= actCountInSlice - 1 ? [6, 6] : undefined };
    
    chartPrediksi.update();
}

updateDynamicCharts(activeYear);

document.getElementById('btnPrevYear').addEventListener('click', () => {
    activeYear--;
    updateDynamicCharts(activeYear);
});
document.getElementById('btnNextYear').addEventListener('click', () => {
    activeYear++;
    updateDynamicCharts(activeYear);
});

@endif



@endif
</script>
@endpush
