@extends('layouts.dashboard')
@section('title', 'Input Transaksi')

@push('styles')
<style>
    /* ── Table Input ── */
    .input-table { width: 100%; border-collapse: separate; border-spacing: 0; }

    .input-table thead th {
        background: #111827; padding: 0.75rem 1rem;
        font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.7);
        text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid #1f2937;
    }

    .input-table tbody td {
        padding: 0.5rem 0.6rem; vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .input-table .row-num {
        width: 40px; color: #adb5bd; font-weight: 600; font-size: 0.85rem; text-align: center;
    }

    .input-table .form-control,
    .input-table .form-select {
        border: 1px solid #e0e0e0; border-radius: 8px;
        font-size: 0.88rem; padding: 0.5rem 0.7rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-table .form-control:focus,
    .input-table .form-select:focus {
        border-color: var(--sap-primary, #1a6b3a);
        box-shadow: 0 0 0 2px rgba(26,107,58,0.12);
    }

    .total-cell { font-weight: 600; color: #1a6b3a; font-size: 0.9rem; padding-left: 0.75rem !important; }

    /* ── Buttons ── */
    .btn-tambah {
        background: #1a6b3a; color: #fff; border: none;
        border-radius: 10px; padding: 0.55rem 1.4rem;
        font-weight: 600; font-size: 0.88rem;
        transition: background 0.2s;
    }
    .btn-tambah:hover { background: #145a2f; color: #fff; }

    .btn-analisa {
        background: linear-gradient(135deg, #e85d04, #f4a100);
        color: #fff; border: none; border-radius: 12px;
        padding: 0.75rem 2.5rem; font-weight: 700; font-size: 1rem;
        box-shadow: 0 4px 15px rgba(232,93,4,0.3);
        transition: all 0.3s;
    }
    .btn-analisa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232,93,4,0.4);
        color: #fff;
    }

    .btn-hapus-row {
        color: #dc3545; background: none; border: none;
        padding: 0.25rem; font-size: 1.1rem; cursor: pointer;
        opacity: 0.4; transition: opacity 0.2s;
    }
    .btn-hapus-row:hover { opacity: 1; }

    /* ── Row animation ── */
    .baris-transaksi {
        animation: fadeSlideIn 0.3s ease forwards;
    }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .date-label { color: #6c757d; font-size: 0.85rem; }

    /* ── Stock info ── */
    .stock-info {
        font-size: 0.72rem; margin-top: 0.2rem;
        padding: 0.15rem 0.5rem; border-radius: 4px;
        display: inline-block;
    }
    .stock-info.ok   { background: #dcfce7; color: #166534; }
    .stock-info.low  { background: #fef9c3; color: #854d0e; }
    .stock-info.out  { background: #fee2e2; color: #991b1b; }

    .sub-kategori-wrap { margin-top: 0.35rem; }
    .sub-kategori-wrap .form-select { font-size: 0.82rem; padding: 0.35rem 0.6rem; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">

        <div class="date-label mb-1">
            @php
                $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                $bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni',
                              'Juli','Agustus','September','Oktober','November','Desember'];
                $now = now();
            @endphp
            {{ $hari[$now->dayOfWeek] }}, {{ $now->day }} {{ $bulanNama[$now->month] }} {{ $now->year }}
        </div>
        <h4 class="fw-bold mb-4">Input Transaksi Baru</h4>

        <form action="{{ route('upload.proses') }}" method="POST" id="formTransaksi">
            @csrf

            {{-- Tanggal Transaksi --}}
            <div class="row mb-4 g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="bulan" class="form-control"
                           value="{{ old('bulan', date('Y-m-d')) }}" required>
                    <div class="form-text"><i class="bi bi-info-circle me-1"></i>Default hari ini. Pilih tanggal lampau untuk input data historis.</div>
                </div>
            </div>

            {{-- Tabel Input Transaksi --}}
            <div class="card card-metric p-0 mb-4">
                <div class="table-responsive">
                    <table class="input-table mobile-cards" id="tabelTransaksi">
                        <thead>
                            <tr>
                                <th style="width:44px"></th>
                                <th style="width:170px">Kategori</th>
                                <th>Produk / Keterangan</th>
                                <th style="width:110px">Jumlah</th>
                                <th style="width:155px">Harga Satuan (Rp)</th>
                                <th style="width:150px">Total (Rp)</th>
                                <th style="width:38px"></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTransaksi">
                            <tr class="baris-transaksi">
                                <td class="row-num">1</td>
                                <td data-label="Kategori">
                                    <select name="kategori[]" class="form-select sel-kategori" required onchange="onKategoriChange(this)">
                                        <option value="">Pilih</option>
                                        <option value="Pemasukan">Pemasukan (Penjualan)</option>
                                        <option value="HPP">HPP (Restock/Bahan)</option>
                                        <option value="Operasional">Operasional (Biaya)</option>
                                    </select>
                                    <div class="sub-kategori-wrap d-none">
                                        <select name="sub_kategori[]" class="form-select sel-sub-kategori" onchange="onSubKategoriChange(this)">
                                            <option value="restock">Restock Produk</option>
                                            <option value="bahan">Bahan Baku / Lainnya</option>
                                        </select>
                                    </div>
                                </td>
                                <td data-label="Produk / Keterangan" class="td-produk">
                                    <select name="product_id[]" class="form-select sel-produk d-none" onchange="onProdukChange(this)">
                                        <option value="">— Pilih Produk —</option>
                                    </select>
                                    <div class="stock-info-wrap"></div>
                                    <input type="text" name="keterangan[]" class="form-control input-ket" placeholder="Keterangan...">
                                </td>
                                <td data-label="Jumlah"><input type="number" name="kuantitas[]" class="form-control input-qty" min="0" value="0" required></td>
                                <td data-label="Harga Satuan (Rp)"><input type="number" name="nilai_satuan[]" class="form-control input-unit" min="0" value="0" required></td>
                                <td data-label="Total (Rp)" class="total-cell">
                                    <span class="display-total">0</span>
                                    <input type="hidden" name="nominal[]" class="input-nominal" value="0">
                                </td>
                                <td data-label="">
                                    <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)" title="Hapus">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 pt-2">
                    <button type="button" class="btn btn-tambah" onclick="tambahBaris()">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Transaksi
                    </button>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-analisa">Tambahkan</button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
let rowCount = 1;
let productsData = [];

// Fetch products on page load
fetch("{{ route('api.produk') }}")
    .then(r => r.json())
    .then(data => { productsData = data; })
    .catch(() => { productsData = []; });

function buildProdukOptions() {
    let html = '<option value="">— Pilih Produk —</option>';
    productsData.forEach(p => {
        html += `<option value="${p.id}" data-harga="${p.harga_jual}" data-modal="${p.harga_modal}" data-stok="${p.stok_saat_ini}" data-satuan="${p.satuan}">${p.nama_produk} (${p.stok_saat_ini} ${p.satuan})</option>`;
    });
    return html;
}

function onKategoriChange(sel) {
    const tr = sel.closest('tr') || sel.closest('.baris-transaksi');
    const kategori = sel.value;
    const subWrap = tr.querySelector('.sub-kategori-wrap');
    const selProduk = tr.querySelector('.sel-produk');
    const inputKet = tr.querySelector('.input-ket');
    const stockWrap = tr.querySelector('.stock-info-wrap');

    // Reset
    subWrap.classList.add('d-none');
    selProduk.classList.add('d-none');
    inputKet.classList.remove('d-none');
    stockWrap.innerHTML = '';

    if (kategori === 'Pemasukan') {
        // Show product dropdown, hide manual input
        selProduk.innerHTML = buildProdukOptions();
        selProduk.classList.remove('d-none');
        inputKet.classList.add('d-none');
    } else if (kategori === 'HPP') {
        // Show sub-kategori
        subWrap.classList.remove('d-none');
        const sub = tr.querySelector('.sel-sub-kategori');
        sub.value = 'restock';
        onSubKategoriChange(sub);
    }
    // Operasional: just manual input (default)
}

function onSubKategoriChange(sel) {
    const tr = sel.closest('tr') || sel.closest('.baris-transaksi');
    const selProduk = tr.querySelector('.sel-produk');
    const inputKet = tr.querySelector('.input-ket');
    const stockWrap = tr.querySelector('.stock-info-wrap');

    if (sel.value === 'restock') {
        selProduk.innerHTML = buildProdukOptions();
        selProduk.classList.remove('d-none');
        inputKet.classList.add('d-none');
    } else {
        selProduk.classList.add('d-none');
        inputKet.classList.remove('d-none');
        stockWrap.innerHTML = '';
    }
}

function onProdukChange(sel) {
    const tr = sel.closest('tr') || sel.closest('.baris-transaksi');
    const opt = sel.options[sel.selectedIndex];
    const inputUnit = tr.querySelector('.input-unit');
    const stockWrap = tr.querySelector('.stock-info-wrap');
    const kategori = tr.querySelector('.sel-kategori').value;

    if (!opt || !opt.value) {
        stockWrap.innerHTML = '';
        return;
    }

    const stok = parseInt(opt.dataset.stok) || 0;
    const satuan = opt.dataset.satuan || 'pcs';

    // Auto-fill price
    if (kategori === 'Pemasukan') {
        inputUnit.value = opt.dataset.harga || 0;
    } else {
        inputUnit.value = opt.dataset.modal || 0;
    }

    // Show stock info
    let cls = 'ok', icon = '✓';
    if (stok <= 0) { cls = 'out'; icon = '✗'; }
    else if (stok <= 10) { cls = 'low'; icon = '⚠'; }

    stockWrap.innerHTML = `<div class="stock-info ${cls}">${icon} Stok: ${stok} ${satuan}</div>`;

    // Recalculate total
    calcRow(tr);
}

const ROW_TEMPLATE = `
    <td class="row-num">__NUM__</td>
    <td data-label="Kategori">
        <select name="kategori[]" class="form-select sel-kategori" required onchange="onKategoriChange(this)">
            <option value="">Pilih</option>
            <option value="Pemasukan">Pemasukan (Penjualan)</option>
            <option value="HPP">HPP (Restock/Bahan)</option>
            <option value="Operasional">Operasional (Biaya)</option>
        </select>
        <div class="sub-kategori-wrap d-none">
            <select name="sub_kategori[]" class="form-select sel-sub-kategori" onchange="onSubKategoriChange(this)">
                <option value="restock">Restock Produk</option>
                <option value="bahan">Bahan Baku / Lainnya</option>
            </select>
        </div>
    </td>
    <td data-label="Produk / Keterangan" class="td-produk">
        <select name="product_id[]" class="form-select sel-produk d-none" onchange="onProdukChange(this)">
            <option value="">— Pilih Produk —</option>
        </select>
        <div class="stock-info-wrap"></div>
        <input type="text" name="keterangan[]" class="form-control input-ket" placeholder="Keterangan...">
    </td>
    <td data-label="Jumlah"><input type="number" name="kuantitas[]" class="form-control input-qty" min="0" value="0" required></td>
    <td data-label="Harga Satuan (Rp)"><input type="number" name="nilai_satuan[]" class="form-control input-unit" min="0" value="0" required></td>
    <td data-label="Total (Rp)" class="total-cell">
        <span class="display-total">0</span>
        <input type="hidden" name="nominal[]" class="input-nominal" value="0">
    </td>
    <td data-label="">
        <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)" title="Hapus">
            <i class="bi bi-x-circle"></i>
        </button>
    </td>`;

function tambahBaris() {
    rowCount++;
    const tr = document.createElement('tr');
    tr.className = 'baris-transaksi';
    tr.innerHTML = ROW_TEMPLATE.replace('__NUM__', rowCount);
    document.getElementById('tbodyTransaksi').appendChild(tr);
    bindCalc(tr);
}

function hapusBaris(btn) {
    const tbody = document.getElementById('tbodyTransaksi');
    if (tbody.querySelectorAll('.baris-transaksi').length <= 1) return alert('Minimal harus ada 1 baris.');
    const tr = btn.closest('tr');
    tr.style.transition = 'all .25s'; tr.style.opacity = '0'; tr.style.transform = 'translateX(15px)';
    setTimeout(() => { tr.remove(); renum(); }, 250);
}

function renum() {
    document.querySelectorAll('.baris-transaksi').forEach((r, i) => {
        r.querySelector('.row-num').textContent = i + 1;
    });
    rowCount = document.querySelectorAll('.baris-transaksi').length;
}

function calcRow(row) {
    const q = parseFloat(row.querySelector('.input-qty').value) || 0;
    const u = parseFloat(row.querySelector('.input-unit').value) || 0;
    const t = q * u;
    row.querySelector('.display-total').textContent = t.toLocaleString('id-ID');
    row.querySelector('.input-nominal').value = t;
}

function bindCalc(row) {
    row.querySelector('.input-qty').addEventListener('input', () => calcRow(row));
    row.querySelector('.input-unit').addEventListener('input', () => calcRow(row));
}

// Bind initial row
document.querySelectorAll('.baris-transaksi').forEach(r => bindCalc(r));
</script>
@endpush
