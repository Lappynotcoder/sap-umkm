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
                    <table class="input-table" id="tabelTransaksi">
                        <thead>
                            <tr>
                                <th style="width:44px"></th>
                                <th style="width:170px">Kategori</th>
                                <th>Keterangan</th>
                                <th style="width:110px">Kuantitas</th>
                                <th style="width:155px">Nilai Satuan (Rp)</th>
                                <th style="width:150px">Total (Rp)</th>
                                <th style="width:38px"></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTransaksi">
                            <tr class="baris-transaksi">
                                <td class="row-num">1</td>
                                <td>
                                    <select name="kategori[]" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="Pemasukan">Pemasukan</option>
                                        <option value="HPP">HPP</option>
                                        <option value="Operasional">Operasional</option>
                                    </select>
                                </td>
                                <td><input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan..."></td>
                                <td><input type="number" name="kuantitas[]" class="form-control input-qty" min="0" value="0" required></td>
                                <td><input type="number" name="nilai_satuan[]" class="form-control input-unit" min="0" value="0" required></td>
                                <td class="total-cell">
                                    <span class="display-total">0</span>
                                    <input type="hidden" name="nominal[]" class="input-nominal" value="0">
                                </td>
                                <td>
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

const ROW_HTML = `
    <td class="row-num">__NUM__</td>
    <td>
        <select name="kategori[]" class="form-select" required>
            <option value="">Pilih</option>
            <option value="Pemasukan">Pemasukan</option>
            <option value="HPP">HPP</option>
            <option value="Operasional">Operasional</option>
        </select>
    </td>
    <td><input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan..."></td>
    <td><input type="number" name="kuantitas[]" class="form-control input-qty" min="0" value="0" required></td>
    <td><input type="number" name="nilai_satuan[]" class="form-control input-unit" min="0" value="0" required></td>
    <td class="total-cell">
        <span class="display-total">0</span>
        <input type="hidden" name="nominal[]" class="input-nominal" value="0">
    </td>
    <td>
        <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)" title="Hapus">
            <i class="bi bi-x-circle"></i>
        </button>
    </td>`;

function tambahBaris() {
    rowCount++;
    const tr = document.createElement('tr');
    tr.className = 'baris-transaksi';
    tr.innerHTML = ROW_HTML.replace('__NUM__', rowCount);
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
