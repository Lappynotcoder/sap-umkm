@extends('layouts.dashboard')
@section('title', $product ? 'Edit Produk' : 'Tambah Produk')

@push('styles')
<style>
    .form-product .form-label {
        font-weight: 600; font-size: 0.85rem; color: #475569;
    }
    .form-product .form-control,
    .form-product .form-select {
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        padding: 0.6rem 1rem; font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-product .form-control:focus,
    .form-product .form-select:focus {
        border-color: #1a6b3a;
        box-shadow: 0 0 0 2px rgba(26,107,58,0.12);
    }
    .form-text { font-size: 0.78rem; }
    .price-group { position: relative; }
    .price-group .prefix {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 0.85rem; font-weight: 600;
    }
    .price-group input { padding-left: 2.5rem; }
    .satuan-hint { font-size: 0.75rem; color: #94a3b8; margin-top: 0.15rem; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="mb-4">
            <a href="{{ route('produk.index') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Produk
            </a>
        </div>

        <h4 class="fw-bold mb-4">
            <i class="bi {{ $product ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2" style="color:#1a6b3a"></i>
            {{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}
        </h4>

        <div class="card card-metric p-4">
            <form method="POST"
                  action="{{ $product ? route('produk.update', $product->id) : route('produk.store') }}"
                  class="form-product">
                @csrf
                @if($product) @method('PUT') @endif

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control" required
                           value="{{ old('nama_produk', $product->nama_produk ?? '') }}"
                           placeholder="Contoh: Nasi Bungkus, Es Teh, Keripik Tempe">
                    @error('nama_produk') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">— Pilih Kategori —</option>
                        @php
                            $kategoris = ['Makanan', 'Minuman', 'Kerajinan', 'Pakaian', 'Sembako', 'Lainnya'];
                            $selected = old('kategori', $product->kategori ?? '');
                        @endphp
                        @foreach($kategoris as $k)
                            <option value="{{ $k }}" {{ $selected == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    {{-- Harga Jual --}}
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="price-group">
                            <span class="prefix">Rp</span>
                            <input type="number" name="harga_jual" class="form-control" required min="0"
                                   value="{{ old('harga_jual', $product->harga_jual ?? 0) }}">
                        </div>
                        <div class="form-text">Harga yang Anda kenakan ke pembeli</div>
                    </div>

                    {{-- Harga Modal --}}
                    <div class="col-md-6">
                        <label class="form-label">Harga Modal (HPP) <span class="text-danger">*</span></label>
                        <div class="price-group">
                            <span class="prefix">Rp</span>
                            <input type="number" name="harga_modal" class="form-control" required min="0"
                                   value="{{ old('harga_modal', $product->harga_modal ?? 0) }}">
                        </div>
                        <div class="form-text">Biaya pokok per item (bahan + produksi)</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    {{-- Satuan --}}
                    <div class="col-md-4">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" class="form-select">
                            @php
                                $satuanList = [
                                    'pcs'    => 'pcs — satuan umum (misal: gorengan, aksesoris)',
                                    'porsi'  => 'porsi — makanan siap saji (misal: nasi bungkus)',
                                    'gelas'  => 'gelas — minuman (misal: es teh, kopi)',
                                    'bungkus'=> 'bungkus — kemasan (misal: keripik, kue)',
                                    'kotak'  => 'kotak — box packaging (misal: kue lapis)',
                                    'kg'     => 'kg — kilogram (misal: beras, gula)',
                                    'liter'  => 'liter — cairan (misal: minyak, susu)',
                                    'botol'  => 'botol — minuman kemasan',
                                    'lusin'  => 'lusin — 12 pcs',
                                    'meter'  => 'meter — kain, pita',
                                ];
                                $selectedSatuan = old('satuan', $product->satuan ?? 'pcs');
                            @endphp
                            @foreach($satuanList as $val => $label)
                                <option value="{{ $val }}" {{ $selectedSatuan == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stok --}}
                    <div class="col-md-4">
                        <label class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                        <input type="number" name="stok_saat_ini" class="form-control" required min="0"
                               value="{{ old('stok_saat_ini', $product->stok_saat_ini ?? 0) }}">
                        <div class="form-text">Boleh diisi 0 jika belum punya stok</div>
                    </div>

                    {{-- Stok Minimum --}}
                    <div class="col-md-4">
                        <label class="form-label">Batas Stok Minimum</label>
                        <input type="number" name="stok_minimum" class="form-control" required min="0"
                               value="{{ old('stok_minimum', $product->stok_minimum ?? 10) }}">
                        <div class="form-text">Peringatan muncul jika stok ≤ angka ini</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary btn-pill">Batal</a>
                    <button type="submit" class="btn btn-pill"
                            style="background:linear-gradient(135deg,#e85d04,#f4a100); color:#fff; font-weight:700; padding:0.6rem 2rem;">
                        <i class="bi bi-check-circle me-1"></i>{{ $product ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
