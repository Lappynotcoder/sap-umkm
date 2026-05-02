@extends('layouts.dashboard')
@section('title', 'Laporan Bulanan')

@push('styles')
<style>
    .filter-bar {
        background: #fff; border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 1rem 1.25rem;
    }

    .report-header {
        text-align: center; padding: 1.5rem 0 1rem;
        border-bottom: 3px double #1a6b3a;
        margin-bottom: 1.5rem;
    }
    .report-header h3 { font-weight: 700; color: #111827; margin-bottom: 0.25rem; }
    .report-header .sub { color: #6c757d; font-size: 0.9rem; }

    .summary-table th { background: #f8f9fa; width: 40%; }
    .summary-table td.val-positif { color: #1a6b3a; font-weight: 700; }
    .summary-table td.val-negatif { color: #dc3545; font-weight: 700; }

    .detail-table thead th {
        background: #406882; color: #fff;
        font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .report-footer {
        margin-top: 2rem; padding-top: 1rem;
        border-top: 1px solid #dee2e6;
        font-size: 0.8rem; color: #adb5bd;
        text-align: center;
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
        .filter-bar, .d-print-none { display: none !important; }
        .card-metric { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        .report-header { border-bottom-color: #000; }
        body { font-size: 11pt; }
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

{{-- ── LAPORAN PRINTABLE ── --}}
<div class="card card-metric p-4">

    {{-- Kop Laporan --}}
    <div class="report-header">
        <h3><i class="bi bi-bar-chart-line-fill me-2" style="color:#1a6b3a"></i>LAPORAN KEUANGAN BULANAN</h3>
        <div class="sub">
            Periode: <strong>{{ DateTime::createFromFormat('!m', $bulanFilter)->format('F') }} {{ $tahunFilter }}</strong>
        </div>
        <div class="sub">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>

    @foreach($laporanBulan as $laporan)
    <div class="mb-4 {{ !$loop->last ? 'pb-4 border-bottom' : '' }}">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-shop me-2" style="color:#1a6b3a"></i>{{ $laporan->nama_umkm }}
            <span class="text-muted fw-normal small">—
                @php
                    try { $tgl = \Carbon\Carbon::parse($laporan->bulan)->translatedFormat('d F Y'); }
                    catch(\Exception $e) { $tgl = $laporan->bulan; }
                @endphp
                {{ $tgl }}
            </span>
        </h5>

        {{-- Tabel Ringkasan --}}
        <table class="table table-bordered summary-table mb-3">
            <tbody>
                <tr>
                    <th>Total Pemasukan</th>
                    <td class="val-positif">Rp {{ number_format($laporan->total_pemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Harga Pokok Penjualan (HPP)</th>
                    <td>Rp {{ number_format($laporan->total_hpp, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Laba Kotor</th>
                    <td class="{{ $laporan->laba_kotor >= 0 ? 'val-positif' : 'val-negatif' }}">
                        Rp {{ number_format($laporan->laba_kotor, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Biaya Operasional</th>
                    <td>Rp {{ number_format($laporan->total_operasional, 0, ',', '.') }}</td>
                </tr>
                <tr style="background:#f0fdf4">
                    <th>Laba Bersih</th>
                    <td class="{{ $laporan->laba_bersih >= 0 ? 'val-positif' : 'val-negatif' }}" style="font-size:1.1rem">
                        Rp {{ number_format($laporan->laba_bersih, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Margin Laba Kotor</th>
                    <td>{{ $laporan->margin_kotor }}%</td>
                </tr>
                <tr>
                    <th>Margin Laba Bersih</th>
                    <td class="{{ $laporan->margin_bersih >= 0 ? 'val-positif' : 'val-negatif' }}">
                        {{ $laporan->margin_bersih }}%
                    </td>
                </tr>
                <tr>
                    <th>Target Balik Modal</th>
                    <td>Rp {{ number_format($laporan->break_even, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Tabel Rincian Transaksi --}}
        @php $detail = is_string($laporan->detail_json) ? json_decode($laporan->detail_json, true) : ($laporan->detail_json ?? []); @endphp
        @if(count($detail) > 0)
        <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-1"></i>Rincian Transaksi</h6>
        <table class="table table-sm table-bordered detail-table mb-0">
            <thead>
                <tr>
                    <th style="width:36px">#</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    @if(isset($detail[0]['kuantitas']))<th class="text-end">Qty</th>@endif
                    @if(isset($detail[0]['nilai_satuan']))<th class="text-end">Satuan (Rp)</th>@endif
                    <th class="text-end">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $i => $row)
                <tr>
                    <td class="text-muted small text-center">{{ $i + 1 }}</td>
                    <td>
                        @php $kat = strtolower($row['kategori'] ?? ''); @endphp
                        <span class="badge {{ $kat == 'pemasukan' ? 'bg-success' : ($kat == 'hpp' ? 'bg-primary' : 'bg-warning text-dark') }}">
                            {{ $row['kategori'] }}
                        </span>
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
        </table>
        @endif
    </div>
    @endforeach

    {{-- Footer --}}
    <div class="report-footer">
        <p>Laporan ini digenerate otomatis oleh <strong>SAP-UMKM</strong> — Sistem Analisis Profit UMKM</p>
    </div>
</div>

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
