@extends('layouts.dashboard')
@section('title', 'Produk & Stok')

@push('styles')
<style>
    .stock-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.3rem 0.75rem; border-radius: 50rem;
        font-size: 0.75rem; font-weight: 600;
    }
    .stock-ok     { background: #dcfce7; color: #166534; }
    .stock-low    { background: #fef9c3; color: #854d0e; }
    .stock-out    { background: #fee2e2; color: #991b1b; }

    .product-kategori {
        display: inline-block; padding: 0.2rem 0.6rem;
        border-radius: 50rem; font-size: 0.72rem; font-weight: 600;
        background: #e0e7ff; color: #3730a3;
    }

    .low-stock-banner {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1.5px solid #f59e0b; border-radius: 12px;
        padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    }

    .product-actions .btn { padding: 0.3rem 0.6rem; font-size: 0.8rem; }

    .satuan-text { color: #6c757d; font-size: 0.8rem; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-box-seam me-2" style="color:#1a6b3a"></i>Produk & Stok
        </h4>
        <span class="text-muted small">Kelola daftar produk dan pantau stok Anda</span>
    </div>
    <a href="{{ route('produk.create') }}" class="btn btn-success btn-sm btn-pill" style="background:#34A853; border:none;">
        <i class="bi bi-plus-circle me-1"></i>Tambah Produk
    </a>
</div>

{{-- Low Stock Alert --}}
@if($lowStockCount > 0)
<div class="low-stock-banner d-flex align-items-center gap-3">
    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
    <div>
        <div class="fw-bold" style="color:#92400e">Perhatian: {{ $lowStockCount }} produk stok rendah!</div>
        <div class="small" style="color:#a16207">Segera lakukan restock melalui menu Keuangan → kategori HPP.</div>
    </div>
</div>
@endif

@if($products->count() > 0)
<div class="card card-metric p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 mobile-cards">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-end">Harga Modal</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($products as $p)
            <tr class="{{ !$p->is_active ? 'opacity-50' : '' }}">
                <td data-label="Produk">
                    <div class="fw-semibold">{{ $p->nama_produk }}</div>
                </td>
                <td data-label="Kategori">
                    @if($p->kategori)
                        <span class="product-kategori">{{ $p->kategori }}</span>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>
                <td data-label="Harga Jual" class="text-end">
                    Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                    <span class="satuan-text">/{{ $p->satuan }}</span>
                </td>
                <td data-label="Harga Modal" class="text-end">
                    Rp {{ number_format($p->harga_modal, 0, ',', '.') }}
                </td>
                <td data-label="Stok" class="text-center fw-bold">
                    {{ $p->stok_saat_ini }} <span class="satuan-text">{{ $p->satuan }}</span>
                </td>
                <td data-label="Status" class="text-center">
                    @if(!$p->is_active)
                        <span class="stock-badge stock-out"><i class="bi bi-x-circle"></i> Nonaktif</span>
                    @elseif($p->stok_saat_ini <= 0)
                        <span class="stock-badge stock-out"><i class="bi bi-x-circle-fill"></i> Habis</span>
                    @elseif($p->stok_saat_ini <= $p->stok_minimum)
                        <span class="stock-badge stock-low"><i class="bi bi-exclamation-triangle-fill"></i> Rendah</span>
                    @else
                        <span class="stock-badge stock-ok"><i class="bi bi-check-circle-fill"></i> Aman</span>
                    @endif
                </td>
                <td data-label="" class="text-center product-actions">
                    <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    @if($p->is_active)
                    <form method="POST" action="{{ route('produk.destroy', $p->id) }}" class="d-inline"
                          onsubmit="return confirm('Nonaktifkan produk {{ $p->nama_produk }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan">
                            <i class="bi bi-archive"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card card-metric p-5 text-center">
    <i class="bi bi-box-seam d-block mb-3 text-muted" style="font-size:3rem; opacity:0.3"></i>
    <h5 class="fw-bold text-muted">Belum Ada Produk</h5>
    <p class="text-muted">Tambahkan produk pertama Anda untuk mulai mengelola stok.</p>
    <a href="{{ route('produk.create') }}" class="btn btn-success mt-2 btn-pill" style="background:#F2AB39; border:none;">
        <i class="bi bi-plus-circle me-1"></i>Tambah Produk Pertama
    </a>
</div>
@endif

@endsection
