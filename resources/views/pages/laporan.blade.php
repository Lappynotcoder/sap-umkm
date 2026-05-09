@extends('layouts.dashboard')
@section('title', 'Laporan Bulanan')

@push('styles')
<style>
    .filter-bar {
        background: #fff; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 1rem 1.25rem;
    }

    /* ── Report Paper ── */
    .report-paper {
        background: #fff; border-radius: 4px;
        box-shadow: 0 2px 12px rgba(0,0,0,.08);
        padding: 2.5rem 3rem;
        max-width: 820px;
        margin: 0 auto;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    .report-kop {
        text-align: center;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 3px double #1a6b3a;
    }
    .report-kop h2 {
        font-weight: 800; font-size: 1.3rem; color: #111827;
        letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 0.15rem;
    }
    .report-kop .subtitle {
        font-size: 1rem; font-weight: 700; color: #1a6b3a;
        margin-bottom: 0.4rem;
    }
    .report-kop .meta {
        font-size: 0.8rem; color: #6c757d;
    }

    /* ── Laporan Laba Rugi ── */
    .pl-statement { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }

    .pl-statement td, .pl-statement th {
        padding: 0.5rem 0.25rem;
        font-size: 0.9rem;
        vertical-align: top;
    }

    .pl-statement .pl-section-head td {
        font-weight: 700; font-size: 0.82rem;
        text-transform: uppercase; letter-spacing: 0.5px;
        color: #406882;
        padding-top: 1.2rem;
        padding-bottom: 0.3rem;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .pl-statement .pl-item td {
        border-bottom: 1px dotted #e5e7eb;
    }
    .pl-statement .pl-item td:first-child {
        padding-left: 1.2rem; color: #374151;
    }

    .pl-statement .pl-subtotal td {
        font-weight: 700;
        border-top: 1.5px solid #334155;
        border-bottom: 1.5px solid #334155;
        padding-top: 0.6rem; padding-bottom: 0.6rem;
    }
    .pl-statement .pl-subtotal td:first-child {
        padding-left: 0.5rem;
    }

    .pl-statement .pl-grandtotal td {
        font-weight: 800; font-size: 1.05rem;
        border-top: 3px double #1a6b3a;
        border-bottom: 3px double #1a6b3a;
        padding: 0.7rem 0.25rem;
        background: #f0fdf4;
    }

    .text-positif { color: #166534; }
    .text-negatif { color: #dc3545; }

    /* ── Rasio Box ── */
    .rasio-row {
        display: flex; gap: 1rem; flex-wrap: wrap;
        margin: 1.5rem 0;
    }
    .rasio-box {
        flex: 1; min-width: 140px;
        text-align: center; padding: 1rem;
        border: 1.5px solid #e2e8f0; border-radius: 8px;
    }
    .rasio-label { font-size: 0.72rem; color: #6c757d; font-weight: 600; text-transform: uppercase; }
    .rasio-value { font-size: 1.2rem; font-weight: 800; margin-top: 0.2rem; }

    /* ── Detail Table ── */
    .detail-section { margin-top: 2rem; }
    .detail-section-title {
        font-size: 0.82rem; font-weight: 700; color: #406882;
        text-transform: uppercase; letter-spacing: 0.5px;
        padding-bottom: 0.4rem;
        border-bottom: 2px solid #406882;
        margin-bottom: 0;
    }

    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table thead th {
        background: #f8f9fa; color: #374151;
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;
        padding: 0.55rem 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }
    .detail-table tbody td {
        font-size: 0.85rem; padding: 0.4rem 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-table tfoot td {
        font-weight: 700; padding: 0.55rem 0.5rem;
        border-top: 2px solid #334155;
        font-size: 0.85rem;
    }

    .btn-cetak {
        background: #F2AB39;
        color: #fff; border: none;
        padding: 0.6rem 1.8rem; font-weight: 600;
        transition: all 0.3s;
    }
    .btn-cetak:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(26,107,58,.3);
        color: #fff;
    }

    /* ── Print Styles ── */
    @media print {
        /* Sembunyikan semua UI navigasi */
        .filter-bar, .d-print-none,
        .sidebar, .top-bar, .breadcrumb,
        nav, header { display: none !important; }

        /* Hapus margin sidebar */
        .main-wrapper, .main-content { margin-left: 0 !important; padding: 0 !important; }
        body { background: #fff !important; font-size: 10pt; margin: 0; }

        /* Paper styling */
        .report-paper {
            box-shadow: none !important;
            padding: 1.5cm 2cm;
            max-width: 100%;
            border: none;
        }

        /* Warna cetak */
        .pl-statement .pl-grandtotal td { background: #f0fdf4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .detail-table thead th { background: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .rasio-box { border-color: #999 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        /* Avoid page break in sections */
        .pl-statement, .detail-section, .rasio-row { break-inside: avoid; }

        /* Hapus URL/header/footer browser saat print */
        @page {
            margin: 1cm 1.5cm;
            size: A4 portrait;
        }
    }

    /* ── Mobile ── */
    @media (max-width: 768px) {
        .report-paper { padding: 1.5rem 1rem; }
        .report-kop h2 { font-size: 1rem; }
        .rasio-row { gap: 0.5rem; }
        .rasio-box { min-width: 100px; padding: 0.7rem 0.5rem; }
        .rasio-value { font-size: 1rem; }
        .pl-statement td, .pl-statement th { font-size: 0.82rem; }
    }
</style>
@endpush

@section('content')

{{-- ── FILTER BAR ── --}}
<div class="filter-bar mb-4 d-print-none">
    <form method="GET" action="{{ route('laporan') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Bulan</label>
            <select name="bulan" class="form-select form-select-sm">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulanFilter == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Tahun</label>
            <select name="tahun" class="form-select form-select-sm">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahunFilter == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-success btn-sm btn-pill" style="background-color: #34A853; border: none;">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
        </div>
        <div class="col-md-3 text-end">
            @php
                $currentYear = (int) date('Y');
                $currentMonth = (int) date('n');
                $isPastMonth = ($tahunFilter < $currentYear) || ($tahunFilter == $currentYear && $bulanFilter < $currentMonth);
            @endphp
            @if($isPastMonth)
                <button type="button" onclick="window.print()" class="btn btn-cetak btn-sm btn-pill">
                    <i class="bi bi-printer me-1"></i>Cetak PDF
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-sm btn-pill" disabled title="Laporan hanya bisa dicetak setelah bulan ini berakhir" style="opacity: 0.6; cursor: not-allowed;">
                    <i class="bi bi-printer me-1"></i>Cetak PDF
                </button>
            @endif
        </div>
    </form>
</div>

@if($laporanBulan->count() > 0)

@foreach($laporanBulan as $laporan)
@php
    $detail = is_string($laporan->detail_json) ? json_decode($laporan->detail_json, true) : ($laporan->detail_json ?? []);
    $periodLabel = \Carbon\Carbon::parse($laporan->bulan)->translatedFormat('F Y');

    // Kelompokkan detail per kategori untuk sub-total
    $grupPemasukan = [];
    $grupHpp = [];
    $grupOps = [];
    foreach ($detail as $d) {
        $katL = strtolower($d['kategori'] ?? '');
        if ($katL === 'pemasukan') $grupPemasukan[] = $d;
        elseif ($katL === 'hpp') $grupHpp[] = $d;
        else $grupOps[] = $d;
    }
@endphp

{{-- ── REPORT PAPER ── --}}
<div class="report-paper mb-4">

    {{-- Kop Surat --}}
    <div class="report-kop">
        <h2>{{ $laporan->nama_umkm }}</h2>
        <div class="subtitle">Laporan Laba Rugi</div>
        <div class="meta">Periode: {{ $periodLabel }}</div>
    </div>

    {{-- ═══ LAPORAN LABA RUGI (P&L Statement) ═══ --}}
    <div class="table-responsive">
        <table class="pl-statement">
        {{-- PENDAPATAN --}}
        <tr class="pl-section-head"><td colspan="2">Pendapatan</td></tr>
        @foreach($grupPemasukan as $gp)
        <tr class="pl-item">
            <td>{{ $gp['keterangan'] ?? 'Penjualan' }}
                @if(isset($gp['tanggal']))
                    <span style="color:#9ca3af; font-size:0.75rem; margin-left:0.3rem;">({{ \Carbon\Carbon::parse($gp['tanggal'])->format('d/m') }})</span>
                @endif
            </td>
            <td class="text-end">Rp {{ number_format($gp['nominal'] ?? 0, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        @if(empty($grupPemasukan))
        <tr class="pl-item"><td>—</td><td class="text-end">Rp 0</td></tr>
        @endif
        <tr class="pl-subtotal">
            <td>Total Pendapatan</td>
            <td class="text-end text-positif">Rp {{ number_format($laporan->total_pemasukan, 0, ',', '.') }}</td>
        </tr>

        {{-- HPP --}}
        <tr class="pl-section-head"><td colspan="2">Harga Pokok Penjualan</td></tr>
        @foreach($grupHpp as $gh)
        <tr class="pl-item">
            <td>{{ $gh['keterangan'] ?? 'Biaya HPP' }}
                @if(isset($gh['tanggal']))
                    <span style="color:#9ca3af; font-size:0.75rem; margin-left:0.3rem;">({{ \Carbon\Carbon::parse($gh['tanggal'])->format('d/m') }})</span>
                @endif
            </td>
            <td class="text-end">(Rp {{ number_format($gh['nominal'] ?? 0, 0, ',', '.') }})</td>
        </tr>
        @endforeach
        @if(empty($grupHpp))
        <tr class="pl-item"><td>—</td><td class="text-end">Rp 0</td></tr>
        @endif
        <tr class="pl-subtotal">
            <td>Laba Kotor</td>
            <td class="text-end {{ $laporan->laba_kotor >= 0 ? 'text-positif' : 'text-negatif' }}">
                Rp {{ number_format($laporan->laba_kotor, 0, ',', '.') }}
            </td>
        </tr>

        {{-- OPERASIONAL --}}
        <tr class="pl-section-head"><td colspan="2">Beban Operasional</td></tr>
        @foreach($grupOps as $go)
        <tr class="pl-item">
            <td>{{ $go['keterangan'] ?? 'Biaya Operasional' }}
                @if(isset($go['tanggal']))
                    <span style="color:#9ca3af; font-size:0.75rem; margin-left:0.3rem;">({{ \Carbon\Carbon::parse($go['tanggal'])->format('d/m') }})</span>
                @endif
            </td>
            <td class="text-end">(Rp {{ number_format($go['nominal'] ?? 0, 0, ',', '.') }})</td>
        </tr>
        @endforeach
        @if(empty($grupOps))
        <tr class="pl-item"><td>—</td><td class="text-end">Rp 0</td></tr>
        @endif

        {{-- LABA BERSIH --}}
        <tr class="pl-grandtotal">
            <td>LABA BERSIH</td>
            <td class="text-end {{ $laporan->laba_bersih >= 0 ? 'text-positif' : 'text-negatif' }}">
                Rp {{ number_format($laporan->laba_bersih, 0, ',', '.') }}
            </td>
        </tr>
    </table>
    </div>

    {{-- ═══ RASIO KEUANGAN ═══ --}}
    <div class="rasio-row">
        <div class="rasio-box">
            <div class="rasio-label">Margin Kotor</div>
            <div class="rasio-value {{ $laporan->margin_kotor >= 0 ? 'text-positif' : 'text-negatif' }}">{{ $laporan->margin_kotor }}%</div>
        </div>
        <div class="rasio-box">
            <div class="rasio-label">Margin Bersih</div>
            <div class="rasio-value {{ $laporan->margin_bersih >= 0 ? 'text-positif' : 'text-negatif' }}">{{ $laporan->margin_bersih }}%</div>
        </div>
        <div class="rasio-box">
            <div class="rasio-label">Break Even Point</div>
            <div class="rasio-value" style="font-size:0.95rem; color:#334155;">Rp {{ number_format($laporan->break_even, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- ═══ RINCIAN TRANSAKSI ═══ --}}
    @if(count($detail) > 0)
    <div class="detail-section">
        <div class="detail-section-title mb-2">
            <i class="bi bi-list-check me-1"></i>Rincian Transaksi
        </div>
        <div class="table-responsive">
            <table class="detail-table">
            <thead>
                <tr>
                    <th style="width:28px">#</th>
                    <th>Tgl</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    @if(isset($detail[0]['kuantitas']))<th class="text-end">Qty</th>@endif
                    @if(isset($detail[0]['nilai_satuan']))<th class="text-end">Harga Satuan</th>@endif
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $i => $row)
                @php $kat = strtolower($row['kategori'] ?? ''); @endphp
                <tr>
                    <td class="text-muted text-center">{{ $i + 1 }}</td>
                    <td class="small text-muted">{{ isset($row['tanggal']) ? \Carbon\Carbon::parse($row['tanggal'])->format('d/m') : '-' }}</td>
                    <td>
                        @if($kat == 'pemasukan')
                            <span style="color:#166534; font-weight:600; font-size:0.78rem;">Pemasukan</span>
                        @elseif($kat == 'hpp')
                            <span style="color:#1e40af; font-weight:600; font-size:0.78rem;">HPP</span>
                        @else
                            <span style="color:#92400e; font-weight:600; font-size:0.78rem;">Operasional</span>
                        @endif
                    </td>
                    <td>{{ $row['keterangan'] ?? '-' }}</td>
                    @if(isset($detail[0]['kuantitas']))
                        <td class="text-end">{{ number_format($row['kuantitas'] ?? 0, 0, ',', '.') }}</td>
                    @endif
                    @if(isset($detail[0]['nilai_satuan']))
                        <td class="text-end">Rp {{ number_format($row['nilai_satuan'] ?? 0, 0, ',', '.') }}</td>
                    @endif
                    <td class="text-end fw-semibold">Rp {{ number_format($row['nominal'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    @php
                        $footCols = 3; // #, tgl, kategori
                        if(isset($detail[0]['kuantitas'])) $footCols++;
                        if(isset($detail[0]['nilai_satuan'])) $footCols++;
                    @endphp
                    <td colspan="{{ $footCols }}" class="text-end">Total Transaksi:</td>
                    <td class="text-end">
                        <strong>{{ count($detail) }} item</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
    @endif

</div>

@endforeach

@else

{{-- Empty State --}}
<div class="card card-metric">
    <div class="text-center py-5" style="color:#adb5bd">
        <i class="bi bi-journal-x d-block mb-3" style="font-size:3rem;opacity:0.3"></i>
        <p class="mb-2">Tidak ada laporan untuk periode <strong>{{ DateTime::createFromFormat('!m', $bulanFilter)->format('F') }} {{ $tahunFilter }}</strong>.</p>
        <p class="small">Coba pilih bulan/tahun lain, atau input transaksi baru.</p>
    </div>
</div>

@endif

@endsection
