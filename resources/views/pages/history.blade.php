@extends('layouts.dashboard')

@section('title', 'Histori Transaksi Penuh')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-clock-history text-primary me-2"></i>Histori Transaksi</h4>
        <p class="text-muted small mb-0">Seluruh riwayat pencatatan transaksi keuangan UMKM Anda.</p>
    </div>
    <form method="GET" action="{{ route('history') }}" class="d-flex align-items-center bg-white p-2 rounded shadow-sm border">
        <label for="tahun" class="me-2 small fw-bold text-muted mb-0"><i class="bi bi-filter me-1"></i>Tahun:</label>
        <select name="tahun" id="tahun" class="form-select form-select-sm border-0" onchange="this.form.submit()" style="width: auto; background-color: #f8f9fa; cursor: pointer;">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

@if($riwayat->count() > 0)
<div class="card card-metric p-3 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 mobile-cards">
            <thead class="table-light">
                <tr>
                    <th>Periode</th>
                    <th class="text-end">Total Pemasukan</th>
                    <th class="text-end">Total Pengeluaran</th>
                    <th class="text-end">Laba Bersih</th>
                    <th>Margin</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($riwayat as $r)
                @php
                    $pengeluaran = $r->total_hpp + $r->total_operasional;
                @endphp
            <tr>
                <td data-label="Periode" class="text-muted fw-medium">
                    @php
                        try { $tgl = \Carbon\Carbon::parse($r->bulan)->translatedFormat('M Y'); }
                        catch(\Exception $e) { $tgl = $r->bulan; }
                    @endphp
                    {{ $tgl }}
                </td>
                <td data-label="Pemasukan" class="text-end text-success">
                    Rp {{ number_format($r->total_pemasukan,0,',','.') }}
                </td>
                <td data-label="Pengeluaran" class="text-end text-danger">
                    Rp {{ number_format($pengeluaran,0,',','.') }}
                </td>
                <td data-label="Laba Bersih" class="text-end {{ $r->laba_bersih >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    Rp {{ number_format($r->laba_bersih,0,',','.') }}
                </td>
                <td data-label="Margin">
                    <span class="badge {{ $r->margin_bersih >= 0 ? 'badge-profit-pos' : 'badge-profit-neg' }}">
                        {{ $r->margin_bersih }}%
                    </span>
                </td>
                <td data-label="" class="text-center">
                    <a href="{{ route('dashboard.show', $r->id) }}" class="btn btn-sm btn-outline-success btn-pill" title="Lihat Detail" style="color: #34A853; border-color: #34A853;">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $riwayat->appends(['tahun' => $tahun])->links('pagination::bootstrap-5') }}
    </div>
</div>
@else
<div class="card card-metric p-5 text-center">
    <div class="empty-state">
        <i class="bi bi-inbox d-block mb-3 text-muted" style="font-size: 3rem;"></i>
        <h5>Belum Ada Riwayat Transaksi</h5>
        <p class="text-muted">Mulai catat transaksi keuangan UMKM Anda sekarang.</p>
        <a href="{{ route('upload.form') }}" class="btn btn-success mt-2 btn-pill" style="background-color: #F2AB39; border-color: #F2AB39;">
            <i class="bi bi-plus-circle me-1"></i>Input Transaksi Pertama
        </a>
    </div>
</div>
@endif

@endsection
