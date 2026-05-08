@extends('layouts.dashboard')
@section('title', 'Analisis — ' . $laporan->nama_umkm)

@push('styles')
<style>
    .metric-card { border-left: 4px solid; border-radius: 10px; }
    .mc-hijau  { border-color: #1a6b3a; }
    .mc-biru   { border-color: #0d6efd; }
    .mc-kuning { border-color: #f4a100; }
    .mc-merah  { border-color: #dc3545; }
    .alert-threshold { border-left: 4px solid #f4a100; background: #fff8e1; }

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

    .insight-box {
        background: linear-gradient(135deg,#e8f5ee,#f8fdf9);
        border-radius: 10px; padding: 1rem 1.25rem;
        border-left: 4px solid #1a6b3a;
    }
    .insight-box .insight-label { font-size: 0.78rem; color: #6c757d; font-weight: 600; text-transform: uppercase; }
    .insight-box .insight-value { font-size: 1.1rem; font-weight: 700; color: #1a6b3a; }
</style>
@endpush

@section('content')

{{-- ── HEADER ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-graph-up me-2" style="color:#1a6b3a"></i>
            Analisis: {{ $laporan->nama_umkm }}
        </h4>
        <span class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i>
            @php
                try { $tglHeader = \Carbon\Carbon::parse($laporan->bulan)->translatedFormat('F Y'); }
                catch(\Exception $e) { $tglHeader = $laporan->bulan; }
            @endphp
            Periode: {{ $tglHeader }}
        </span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('upload.form') }}" class="btn btn-success btn-sm d-print-none">
            <i class="bi bi-plus-circle me-1"></i>Analisis Baru
        </a>
        <a href="{{ route('riwayat') }}" class="btn btn-outline-secondary btn-sm d-print-none">
            <i class="bi bi-list-ul me-1"></i>Dashboard
        </a>
        <button onclick="window.print()" class="btn btn-outline-primary btn-sm d-print-none">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
        <form action="{{ route('laporan.hapus', $laporan->id) }}" method="POST"
              onsubmit="return confirm('Yakin hapus laporan ini?')" class="d-print-none">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i>Hapus
            </button>
        </form>
    </div>
</div>

{{-- ── PERINGATAN ── --}}
@if($laporan->margin_bersih < 10 && $laporan->margin_bersih >= 0)
<div class="alert alert-threshold alert-dismissible fade show mb-4 rounded-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
    <strong>Perhatian:</strong> Margin laba bersih hanya <strong>{{ $laporan->margin_bersih }}%</strong>.
    Angka sehat UMKM umumnya di atas 10%.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@elseif($laporan->margin_bersih < 0)
<div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3" role="alert">
    <i class="bi bi-x-octagon-fill me-2"></i>
    <strong>Rugi:</strong> Bisnis mengalami kerugian <strong>Rp {{ number_format(abs($laporan->laba_bersih),0,',','.') }}</strong>.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── KARTU METRIK ── --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Pemasukan',   'value'=> $laporan->total_pemasukan,   'icon'=>'bi-cash-coin',      'kelas'=>'mc-hijau',  'color'=>'#1a6b3a'],
        ['label'=>'Total HPP',         'value'=> $laporan->total_hpp,         'icon'=>'bi-box-seam',       'kelas'=>'mc-biru',   'color'=>'#0d6efd'],
        ['label'=>'Biaya Operasional', 'value'=> $laporan->total_operasional, 'icon'=>'bi-wallet2',        'kelas'=>'mc-kuning', 'color'=>'#f4a100'],
        ['label'=>'Laba Kotor',        'value'=> $laporan->laba_kotor,        'icon'=>'bi-graph-up-arrow', 'kelas'=>'mc-hijau',  'color'=>'#1a6b3a'],
        ['label'=>'Laba Bersih',       'value'=> $laporan->laba_bersih,       'icon'=>'bi-trophy-fill',    'kelas'=> $laporan->laba_bersih >= 0 ? 'mc-hijau' : 'mc-merah', 'color'=> $laporan->laba_bersih >= 0 ? '#1a6b3a' : '#dc3545'],
        ['label'=>'Break Even Point',  'value'=> $laporan->break_even,        'icon'=>'bi-bullseye',       'kelas'=>'mc-biru',   'color'=>'#0d6efd'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-sm-6 col-md-4">
        <div class="card metric-card {{ $c['kelas'] }} p-3 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;background:{{ $c['color'] }}22">
                    <i class="bi {{ $c['icon'] }} fs-5" style="color:{{ $c['color'] }}"></i>
                </div>
                <div>
                    <div class="small text-muted">{{ $c['label'] }}</div>
                    <div class="fw-bold fs-6" style="color:{{ $c['color'] }}">
                        Rp {{ number_format($c['value'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── BADGE MARGIN + STATUS ── --}}
<div class="d-flex gap-3 mb-4 flex-wrap">
    <div class="card p-3 flex-fill text-center shadow-sm">
        <div class="small text-muted mb-1">Margin Laba Kotor</div>
        <div class="fs-4 fw-bold {{ $laporan->margin_kotor >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $laporan->margin_kotor }}%
        </div>
    </div>
    <div class="card p-3 flex-fill text-center shadow-sm">
        <div class="small text-muted mb-1">Margin Laba Bersih</div>
        <div class="fs-4 fw-bold {{ $laporan->margin_bersih >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $laporan->margin_bersih }}%
        </div>
    </div>
    <div class="card p-3 flex-fill text-center shadow-sm">
        <div class="small text-muted mb-1">Status Profitabilitas</div>
        <div class="fs-5 fw-bold">
            @if($laporan->laba_bersih > 0)
                <span class="badge badge-profit-pos px-3 py-2">✅ Untung</span>
            @elseif($laporan->laba_bersih == 0)
                <span class="badge bg-secondary px-3 py-2">➖ Impas</span>
            @else
                <span class="badge badge-profit-neg px-3 py-2">❌ Rugi</span>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ── GRAFIK DETAIL ── --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}

{{-- Row 1: Bar Chart + Doughnut --}}
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-bar-chart-fill"></i>Waterfall Keuangan</div>
            <canvas id="chartWaterfall" height="220"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-pie-chart-fill"></i>Komposisi Penggunaan Dana</div>
            <canvas id="chartPie" height="220"></canvas>
        </div>
    </div>
</div>

{{-- Row 2: Rasio HPP + Radar (jika multi-bulan) --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-bullseye"></i>Posisi terhadap Break Even Point</div>
            <canvas id="chartBep" height="180"></canvas>
            <div class="mt-3 text-center">
                @if($laporan->total_pemasukan >= $laporan->break_even)
                    <span class="badge bg-success px-3 py-2">✅ Pemasukan melampaui BEP</span>
                @else
                    @php $kurang = $laporan->break_even - $laporan->total_pemasukan; @endphp
                    <span class="badge bg-warning text-dark px-3 py-2">⚠️ Butuh Rp {{ number_format($kurang,0,',','.') }} lagi</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-diagram-3"></i>Proporsi Biaya vs Laba</div>
            <canvas id="chartStacked" height="180"></canvas>
        </div>
    </div>
</div>

{{-- Row 3: Tren Multi-Bulan (hanya jika > 1 data) --}}
@if($trendData->count() > 1)
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-graph-up"></i>Tren Pemasukan, HPP & Laba — {{ $laporan->nama_umkm }}</div>
            <canvas id="chartTren" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-title"><i class="bi bi-speedometer"></i>Tren Margin Profitabilitas</div>
            <canvas id="chartMarginTren" height="200"></canvas>
        </div>
    </div>
</div>
@endif

{{-- ── TABEL DETAIL TRANSAKSI ── --}}
@if(count($detail) > 0)
<div class="card card-metric p-3 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-table me-2" style="color:#1a6b3a"></i>Rincian Transaksi</h6>
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0 mobile-cards">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    @if(isset($detail[0]['tanggal']))<th>Tanggal</th>@endif
                    <th>Kategori</th>
                    @if(isset($detail[0]['keterangan']))<th>Keterangan</th>@endif
                    @if(isset($detail[0]['kuantitas']))<th class="text-end">Kuantitas</th>@endif
                    @if(isset($detail[0]['nilai_satuan']))<th class="text-end">Nilai Satuan</th>@endif
                    <th class="text-end">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $i => $baris)
                <tr>
                    <td data-label="#" class="text-muted small row-num-cell">{{ $i+1 }}</td>
                    @if(isset($detail[0]['tanggal']))
                        <td data-label="Tanggal" class="small">
                            {{ isset($baris['tanggal']) ? \Carbon\Carbon::parse($baris['tanggal'])->format('d/m') : '-' }}
                        </td>
                    @endif
                    <td data-label="Kategori">
                        @php $kat = strtolower($baris['kategori'] ?? '') @endphp
                        <span class="badge
                            {{ $kat == 'pemasukan' ? 'bg-success' : ($kat == 'hpp' ? 'bg-primary' : 'bg-warning text-dark') }}">
                            {{ $baris['kategori'] }}
                        </span>
                    </td>
                    @if(isset($detail[0]['keterangan']))
                        <td data-label="Keterangan" class="text-muted small">{{ $baris['keterangan'] ?? '-' }}</td>
                    @endif
                    @if(isset($detail[0]['kuantitas']))
                        <td data-label="Kuantitas" class="text-end">{{ number_format($baris['kuantitas'] ?? 0, 0, ',', '.') }}</td>
                    @endif
                    @if(isset($detail[0]['nilai_satuan']))
                        <td data-label="Nilai Satuan" class="text-end">Rp {{ number_format($baris['nilai_satuan'] ?? 0, 0, ',', '.') }}</td>
                    @endif
                    <td data-label="Total" class="text-end fw-semibold">Rp {{ number_format($baris['nominal'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    @php
                        $colSpan = 2;
                        if(isset($detail[0]['tanggal'])) $colSpan++;
                        if(isset($detail[0]['keterangan'])) $colSpan++;
                        if(isset($detail[0]['kuantitas'])) $colSpan++;
                        if(isset($detail[0]['nilai_satuan'])) $colSpan++;
                    @endphp
                    <td colspan="{{ $colSpan }}" class="fw-bold text-end">Total Pemasukan:</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($laporan->total_pemasukan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

{{-- ── INSIGHT BOX ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="insight-box h-100">
            <div class="insight-label">Rasio HPP terhadap Pemasukan</div>
            <div class="insight-value">
                {{ $laporan->total_pemasukan > 0 ? round(($laporan->total_hpp / $laporan->total_pemasukan) * 100, 1) : 0 }}%
            </div>
            <div class="small text-muted mt-1">Idealnya di bawah 60% untuk UMKM kuliner</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="insight-box h-100">
            <div class="insight-label">Rasio Operasional terhadap Pemasukan</div>
            <div class="insight-value">
                {{ $laporan->total_pemasukan > 0 ? round(($laporan->total_operasional / $laporan->total_pemasukan) * 100, 1) : 0 }}%
            </div>
            <div class="small text-muted mt-1">Semakin kecil semakin efisien</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="insight-box h-100">
            <div class="insight-label">Efisiensi Laba per Rupiah</div>
            <div class="insight-value">
                Rp {{ $laporan->total_pemasukan > 0 ? number_format($laporan->laba_bersih / $laporan->total_pemasukan * 100, 0, ',', '.') : 0 }} per Rp 100
            </div>
            <div class="small text-muted mt-1">Dari setiap Rp 100 pemasukan, berapa yang jadi laba</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const data = {
    pemasukan:   {{ $laporan->total_pemasukan }},
    hpp:         {{ $laporan->total_hpp }},
    operasional: {{ $laporan->total_operasional }},
    labaKotor:   {{ $laporan->laba_kotor }},
    labaBersih:  {{ $laporan->laba_bersih }},
    breakEven:   {{ $laporan->break_even }},
};

const fmtRp = v => 'Rp ' + Math.abs(v).toLocaleString('id-ID');
const fmtRpShort = v => {
    if (Math.abs(v) >= 1e6) return 'Rp ' + (v/1e6).toFixed(1) + 'jt';
    if (Math.abs(v) >= 1e3) return 'Rp ' + (v/1e3).toFixed(0) + 'rb';
    return 'Rp ' + v;
};

// ══════════════════════════════════════════════════════════════════════
// 1. WATERFALL: Pemasukan → HPP → Operasional → Laba
// ══════════════════════════════════════════════════════════════════════
new Chart(document.getElementById('chartWaterfall'), {
    type: 'bar',
    data: {
        labels: ['Pemasukan', 'HPP', 'Operasional', 'Laba Kotor', 'Laba Bersih'],
        datasets: [{
            label: 'Rp',
            data: [data.pemasukan, data.hpp, data.operasional, data.labaKotor, data.labaBersih],
            backgroundColor: [
                '#1a6b3a',
                '#0d6efd',
                '#f4a100',
                '#2e9e5b',
                data.labaBersih >= 0 ? '#1a6b3a' : '#dc3545'
            ],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => fmtRp(ctx.parsed.y) } }
        },
        scales: { y: { ticks: { callback: fmtRpShort } } }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 2. DOUGHNUT: Komposisi Penggunaan Dana
// ══════════════════════════════════════════════════════════════════════
new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: ['HPP', 'Operasional', 'Laba Bersih'],
        datasets: [{
            data: [
                Math.max(0, data.hpp),
                Math.max(0, data.operasional),
                Math.max(0, data.labaBersih),
            ],
            backgroundColor: ['#0d6efd','#f4a100','#1a6b3a'],
            borderWidth: 2,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '55%',
        plugins: {
            tooltip: { callbacks: { label: ctx => ctx.label + ': ' + fmtRp(ctx.parsed) } },
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } }
        }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 3. BEP: Horizontal bar comparing pemasukan vs BEP
// ══════════════════════════════════════════════════════════════════════
new Chart(document.getElementById('chartBep'), {
    type: 'bar',
    data: {
        labels: ['Break Even Point', 'Pemasukan Aktual'],
        datasets: [{
            label: 'Rp',
            data: [data.breakEven, data.pemasukan],
            backgroundColor: ['#6c757d', data.pemasukan >= data.breakEven ? '#1a6b3a' : '#dc3545'],
            borderRadius: 8,
            barThickness: 35,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => fmtRp(ctx.parsed.x) } }
        },
        scales: {
            x: { ticks: { callback: fmtRpShort } }
        }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 4. STACKED BAR: Proporsi dalam 100%
// ══════════════════════════════════════════════════════════════════════
const total = data.hpp + data.operasional + Math.max(0, data.labaBersih);
const pctHpp = total > 0 ? (data.hpp / total * 100).toFixed(1) : 0;
const pctOps = total > 0 ? (data.operasional / total * 100).toFixed(1) : 0;
const pctLaba = total > 0 ? (Math.max(0, data.labaBersih) / total * 100).toFixed(1) : 0;

new Chart(document.getElementById('chartStacked'), {
    type: 'bar',
    data: {
        labels: ['Distribusi Pemasukan'],
        datasets: [
            { label: 'HPP (' + pctHpp + '%)',         data: [pctHpp],  backgroundColor: '#0d6efd', borderRadius: 4 },
            { label: 'Operasional (' + pctOps + '%)', data: [pctOps],  backgroundColor: '#f4a100', borderRadius: 4 },
            { label: 'Laba (' + pctLaba + '%)',        data: [pctLaba], backgroundColor: '#1a6b3a', borderRadius: 4 },
        ]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12 } },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label } }
        },
        scales: {
            x: { stacked: true, max: 100, ticks: { callback: v => v + '%' } },
            y: { stacked: true, display: false }
        }
    }
});

// ══════════════════════════════════════════════════════════════════════
// 5 & 6. TREN MULTI-BULAN (hanya jika > 1 data)
// ══════════════════════════════════════════════════════════════════════
@if($trendData->count() > 1)
const trendLabels     = {!! json_encode($trendData->pluck('bulan')->map(function($d){ try{ return \Carbon\Carbon::parse($d)->format('M Y'); } catch(\Exception $e){ return $d; } })) !!};
const trendPemasukan  = {!! json_encode($trendData->pluck('total_pemasukan')) !!};
const trendHpp        = {!! json_encode($trendData->pluck('total_hpp')) !!};
const trendOps        = {!! json_encode($trendData->pluck('total_operasional')) !!};
const trendLaba       = {!! json_encode($trendData->pluck('laba_bersih')) !!};
const trendMarginK    = {!! json_encode($trendData->pluck('margin_kotor')) !!};
const trendMarginB    = {!! json_encode($trendData->pluck('margin_bersih')) !!};

// 5. Tren Pemasukan + HPP + Laba
new Chart(document.getElementById('chartTren'), {
    type: 'bar',
    data: {
        labels: trendLabels,
        datasets: [
            { label: 'Pemasukan',   data: trendPemasukan, backgroundColor: 'rgba(26,107,58,0.7)',  borderRadius: 4, order: 2 },
            { label: 'HPP',         data: trendHpp,       backgroundColor: 'rgba(13,110,253,0.6)', borderRadius: 4, order: 2 },
            { label: 'Operasional', data: trendOps,       backgroundColor: 'rgba(244,161,0,0.6)',  borderRadius: 4, order: 2 },
            {
                label: 'Laba Bersih', data: trendLaba,
                type: 'line', borderColor: '#1a6b3a', backgroundColor: 'rgba(26,107,58,.08)',
                fill: true, tension: 0.35, pointRadius: 5, pointBackgroundColor: '#1a6b3a',
                borderWidth: 2.5, order: 1
            },
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

// 6. Tren Margin
new Chart(document.getElementById('chartMarginTren'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [
            {
                label: 'Margin Kotor',
                data: trendMarginK,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,.08)',
                fill: true, tension: 0.35,
                pointRadius: 5, pointBackgroundColor: '#0d6efd',
                borderWidth: 2.5,
            },
            {
                label: 'Margin Bersih',
                data: trendMarginB,
                borderColor: '#1a6b3a',
                backgroundColor: 'rgba(26,107,58,.08)',
                fill: true, tension: 0.35,
                pointRadius: 5, pointBackgroundColor: '#1a6b3a',
                borderWidth: 2.5,
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
        scales: {
            y: { ticks: { callback: v => v + '%' } }
        }
    }
});
@endif
</script>
@endpush
