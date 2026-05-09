<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LaporanAnalisis;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AnalisisController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // HALAMAN BERANDA
    // ──────────────────────────────────────────────────────────────────────
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('riwayat');
        }
        return view('pages.home');
    }


    // ──────────────────────────────────────────────────────────────────────
    // HALAMAN FORM UPLOAD
    // ──────────────────────────────────────────────────────────────────────
    public function showUpload()
    {
        return view('pages.upload');
    }

    // ──────────────────────────────────────────────────────────────────────
    // PROSES INPUT TRANSAKSI MANUAL + INVENTORY INTEGRATION
    // Menerima array transaksi dari form, menghitung agregasi,
    // dan otomatis memperbarui stok produk jika terkait.
    // ──────────────────────────────────────────────────────────────────────
    public function prosesUpload(Request $request)
    {
        $request->validate([
            'bulan'            => 'required|date',
            'kategori'         => 'required|array|min:1',
            'kategori.*'       => 'required|in:Pemasukan,HPP,Operasional',
            'keterangan'       => 'required|array',
            'keterangan.*'     => 'nullable|string|max:200',
            'kuantitas'        => 'required|array',
            'kuantitas.*'      => 'required|numeric|min:0',
            'nilai_satuan'     => 'required|array',
            'nilai_satuan.*'   => 'required|numeric|min:0',
            'nominal'          => 'required|array',
            'nominal.*'        => 'required|numeric|min:0',
        ], [
            'bulan.required'      => 'Tanggal transaksi wajib diisi.',
            'bulan.date'          => 'Format tanggal tidak valid.',
            'kategori.required'   => 'Minimal harus ada 1 transaksi.',
            'kategori.*.required' => 'Kategori wajib dipilih untuk setiap baris.',
            'kategori.*.in'       => 'Kategori harus Pemasukan, HPP, atau Operasional.',
        ]);

        // Bangun array detail dan hitung agregasi
        $detail = [];
        $totalPemasukan   = 0;
        $totalHpp         = 0;
        $totalOperasional = 0;

        $kategoris      = $request->kategori;
        $keterangans    = $request->keterangan;
        $kuantitasList  = $request->kuantitas;
        $satuanList     = $request->nilai_satuan;
        $nominalList    = $request->nominal;
        $productIds     = $request->product_id ?? [];
        $subKategoris   = $request->sub_kategori ?? [];

        for ($i = 0; $i < count($kategoris); $i++) {
            $kat         = $kategoris[$i];
            $ket         = $keterangans[$i] ?? '';
            $qty         = floatval($kuantitasList[$i] ?? 0);
            $satuan      = floatval($satuanList[$i] ?? 0);
            $nominal     = $qty * $satuan; // recalculate server-side for safety
            $productId   = $productIds[$i] ?? null;
            $subKat      = $subKategoris[$i] ?? null;

            // ── Inventory Integration ──
            if ($productId && $productId != '') {
                $product = Product::where('user_id', Auth::id())->find($productId);

                if ($product) {
                    $katLower = strtolower($kat);

                    if ($katLower === 'pemasukan') {
                        // Penjualan: kurangi stok, auto-fill keterangan & harga
                        $product->decrement('stok_saat_ini', intval($qty));
                        if (empty($ket)) {
                            $ket = 'Penjualan ' . $product->nama_produk;
                        }
                        $satuan = $product->harga_jual;
                        $nominal = $qty * $satuan;
                    } elseif ($katLower === 'hpp' && $subKat === 'restock') {
                        // Restock: tambah stok
                        $product->increment('stok_saat_ini', intval($qty));
                        if (empty($ket)) {
                            $ket = 'Restock ' . $product->nama_produk;
                        }
                    }
                }
            }

            $detail[] = [
                'kategori'     => $kat,
                'keterangan'   => $ket,
                'kuantitas'    => $qty,
                'nilai_satuan' => $satuan,
                'nominal'      => $nominal,
                'product_id'   => $productId ?: null,
                'tanggal'      => $request->bulan, // simpan tanggal asli per-item
            ];

            $katLower = strtolower($kat);
            if ($katLower === 'pemasukan')   $totalPemasukan   += $nominal;
            if ($katLower === 'hpp')         $totalHpp         += $nominal;
            if ($katLower === 'operasional') $totalOperasional += $nominal;
        }

        // ── Cari apakah sudah ada record untuk bulan yang sama ──
        $tanggalInput = \Carbon\Carbon::parse($request->bulan);
        $bulanNum     = $tanggalInput->month;
        $tahunNum     = $tanggalInput->year;

        $existing = LaporanAnalisis::where('user_id', Auth::id())
            ->whereMonth('bulan', $bulanNum)
            ->whereYear('bulan', $tahunNum)
            ->first();

        if ($existing) {
            // ── GABUNGKAN ke record yang sudah ada ──
            $existingDetail = is_string($existing->detail_json)
                ? json_decode($existing->detail_json, true) ?? []
                : ($existing->detail_json ?? []);

            // Append transaksi baru
            $combinedDetail = array_merge($existingDetail, $detail);

            // Hitung ulang semua agregasi dari seluruh data bulan ini
            $totalPemasukan = 0;
            $totalHpp = 0;
            $totalOperasional = 0;
            foreach ($combinedDetail as $row) {
                $katL = strtolower($row['kategori'] ?? '');
                $nom  = floatval($row['nominal'] ?? 0);
                if ($katL === 'pemasukan')   $totalPemasukan   += $nom;
                if ($katL === 'hpp')         $totalHpp         += $nom;
                if ($katL === 'operasional') $totalOperasional += $nom;
            }

            $labaKotor    = $totalPemasukan - $totalHpp;
            $labaBersih   = $labaKotor - $totalOperasional;
            $marginKotor  = $totalPemasukan > 0 ? round(($labaKotor  / $totalPemasukan) * 100, 2) : 0;
            $marginBersih = $totalPemasukan > 0 ? round(($labaBersih / $totalPemasukan) * 100, 2) : 0;

            $rasioHpp = $totalPemasukan > 0 ? ($totalHpp / $totalPemasukan) : 0;
            $bep = (1 - $rasioHpp) > 0 ? ($totalOperasional / (1 - $rasioHpp)) : 0;

            $existing->update([
                'total_pemasukan'  => $totalPemasukan,
                'total_hpp'        => $totalHpp,
                'total_operasional'=> $totalOperasional,
                'laba_kotor'       => $labaKotor,
                'laba_bersih'      => $labaBersih,
                'margin_kotor'     => $marginKotor,
                'margin_bersih'    => $marginBersih,
                'break_even'       => $bep,
                'detail_json'      => json_encode($combinedDetail),
            ]);

            $laporan = $existing;
        } else {
            // ── Buat record baru (belum ada data bulan ini) ──
            $labaKotor   = $totalPemasukan - $totalHpp;
            $labaBersih  = $labaKotor - $totalOperasional;
            $marginKotor  = $totalPemasukan > 0 ? round(($labaKotor  / $totalPemasukan) * 100, 2) : 0;
            $marginBersih = $totalPemasukan > 0 ? round(($labaBersih / $totalPemasukan) * 100, 2) : 0;

            $rasioHpp = $totalPemasukan > 0 ? ($totalHpp / $totalPemasukan) : 0;
            $bep = (1 - $rasioHpp) > 0 ? ($totalOperasional / (1 - $rasioHpp)) : 0;

            // Gunakan tanggal 1 bulan ini sebagai representasi periode
            $laporan = LaporanAnalisis::create([
                'user_id'          => Auth::id(),
                'nama_umkm'        => Auth::user()->nama_umkm ?? 'UMKM ' . Auth::user()->name,
                'bulan'            => $tanggalInput->startOfMonth()->format('Y-m-d'),
                'file_path'        => 'manual_input',
                'total_pemasukan'  => $totalPemasukan,
                'total_hpp'        => $totalHpp,
                'total_operasional'=> $totalOperasional,
                'laba_kotor'       => $labaKotor,
                'laba_bersih'      => $labaBersih,
                'margin_kotor'     => $marginKotor,
                'margin_bersih'    => $marginBersih,
                'break_even'       => $bep,
                'detail_json'      => json_encode($detail),
            ]);
        }

        return redirect()->route('dashboard.show', $laporan->id)
            ->with('success', 'Transaksi berhasil ditambahkan ke laporan ' . $tanggalInput->translatedFormat('F Y') . '!');
    }

    // ──────────────────────────────────────────────────────────────────────
    // FASE 5 — FUNGSI RENDER VISUALISASI (Dashboard)
    // Mengirim data dari Controller ke Blade view.
    // ──────────────────────────────────────────────────────────────────────
    public function dashboard($id)
    {
        $laporan       = LaporanAnalisis::where('user_id', Auth::id())->findOrFail($id);
        $detail        = is_string($laporan->detail_json) ? json_decode($laporan->detail_json, true) ?? [] : $laporan->detail_json ?? [];

        // Ambil semua laporan UMKM yang sama untuk grafik tren multi-bulan
        $trendData     = LaporanAnalisis::where('user_id', Auth::id())
            ->where('nama_umkm', $laporan->nama_umkm)
            ->orderBy('bulan')
            ->get(['bulan', 'total_pemasukan', 'total_hpp', 'total_operasional', 'laba_kotor', 'laba_bersih', 'margin_kotor', 'margin_bersih', 'break_even']);

        return view('pages.dashboard', compact('laporan', 'detail', 'trendData'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // DASHBOARD + RIWAYAT ANALISIS
    // Menampilkan ringkasan akumulasi + grafik + tabel riwayat
    // ──────────────────────────────────────────────────────────────────────
    public function riwayat(Request $request)
    {
        $userId  = Auth::id();
        $semua   = LaporanAnalisis::where('user_id', $userId);
        $riwayat = LaporanAnalisis::where('user_id', $userId)->latest()->take(10)->get();

        // Ringkasan akumulasi seluruh data
        $summary = [
            'total_pemasukan'   => (clone $semua)->sum('total_pemasukan'),
            'total_hpp'         => (clone $semua)->sum('total_hpp'),
            'total_operasional' => (clone $semua)->sum('total_operasional'),
            'laba_kotor'        => (clone $semua)->sum('laba_kotor'),
            'laba_bersih'       => (clone $semua)->sum('laba_bersih'),
            'jumlah_laporan'    => (clone $semua)->count(),
        ];
        $summary['margin_bersih'] = $summary['total_pemasukan'] > 0
            ? round(($summary['laba_bersih'] / $summary['total_pemasukan']) * 100, 2) : 0;

        // Ambil daftar tahun yang tersedia
        $availableYears = LaporanAnalisis::where('user_id', $userId)
            ->selectRaw('YEAR(bulan) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($availableYears->isEmpty()) {
            $availableYears->push(date('Y'));
        }

        // Data bulanan untuk grafik bar (tahun yang dipilih)
        $tahun = $request->get('tahun', $availableYears->first() ?? date('Y'));
        $bulanan = LaporanAnalisis::where('user_id', $userId)
            ->whereYear('bulan', $tahun)
            ->selectRaw('MONTH(bulan) as bln, SUM(total_pemasukan) as pemasukan, SUM(total_hpp + total_operasional) as pengeluaran')
            ->groupByRaw('MONTH(bulan)')
            ->orderByRaw('MONTH(bulan)')
            ->get();

        // Build 12-month array
        $chartBulanan = [];
        $namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        for ($m = 1; $m <= 12; $m++) {
            $found = $bulanan->firstWhere('bln', $m);
            $chartBulanan[] = [
                'bulan'       => $namaBulan[$m],
                'pemasukan'   => $found ? (float) $found->pemasukan : 0,
                'pengeluaran' => $found ? (float) $found->pengeluaran : 0,
            ];
        }

        // ── Inventory Widgets ──
        $lowStockProducts = Product::where('user_id', $userId)
            ->active()
            ->lowStock()
            ->orderBy('stok_saat_ini')
            ->take(5)
            ->get();

        // Top selling products (aggregate from detail_json)
        $topProducts = collect();
        $allLaporan = LaporanAnalisis::where('user_id', $userId)->get();
        $productSales = [];
        foreach ($allLaporan as $lap) {
            $details = is_string($lap->detail_json) ? json_decode($lap->detail_json, true) : ($lap->detail_json ?? []);
            foreach ($details as $d) {
                if (isset($d['product_id']) && $d['product_id'] && strtolower($d['kategori'] ?? '') === 'pemasukan') {
                    $pid = $d['product_id'];
                    if (!isset($productSales[$pid])) {
                        $productSales[$pid] = ['qty' => 0, 'revenue' => 0];
                    }
                    $productSales[$pid]['qty'] += ($d['kuantitas'] ?? 0);
                    $productSales[$pid]['revenue'] += ($d['nominal'] ?? 0);
                }
            }
        }
        // Resolve product names and sort
        if (!empty($productSales)) {
            arsort($productSales);
            $topIds = array_slice(array_keys($productSales), 0, 5);
            $productNames = Product::whereIn('id', $topIds)->pluck('nama_produk', 'id');
            foreach ($topIds as $pid) {
                if ($productNames->has($pid)) {
                    $topProducts->push((object)[
                        'nama'    => $productNames[$pid],
                        'qty'     => $productSales[$pid]['qty'],
                        'revenue' => $productSales[$pid]['revenue'],
                    ]);
                }
            }
        }

        return view('pages.riwayat', compact(
            'riwayat', 'summary', 'chartBulanan', 'tahun', 'availableYears',
            'lowStockProducts', 'topProducts'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // RIWAYAT TRANSAKSI PENUH (PAGINATED & TAHUN)
    // ──────────────────────────────────────────────────────────────────────
    public function history(Request $request)
    {
        $userId = Auth::id();

        // Ambil daftar tahun yang tersedia
        $availableYears = LaporanAnalisis::where('user_id', $userId)
            ->selectRaw('YEAR(bulan) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($availableYears->isEmpty()) {
            $availableYears->push(date('Y'));
        }

        $tahun = $request->get('tahun', $availableYears->first() ?? date('Y'));

        $riwayat = LaporanAnalisis::where('user_id', $userId)
            ->whereYear('bulan', $tahun)
            ->latest()
            ->paginate(15);

        return view('pages.history', compact('riwayat', 'tahun', 'availableYears'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // HALAMAN ANALISIS (GRAFIK & INSIGHT LENGKAP)
    // Menampilkan analisis agregat seluruh data user dengan grafik detail.
    // ──────────────────────────────────────────────────────────────────────
    public function analisis()
    {
        $userId = Auth::id();
        $semua  = LaporanAnalisis::where('user_id', $userId);
        $count  = (clone $semua)->count();

        if ($count === 0) {
            return view('pages.analisis', ['hasData' => false]);
        }

        // Ringkasan akumulasi
        $summary = [
            'total_pemasukan'   => (clone $semua)->sum('total_pemasukan'),
            'total_hpp'         => (clone $semua)->sum('total_hpp'),
            'total_operasional' => (clone $semua)->sum('total_operasional'),
            'laba_kotor'        => (clone $semua)->sum('laba_kotor'),
            'laba_bersih'       => (clone $semua)->sum('laba_bersih'),
            'jumlah_laporan'    => $count,
        ];
        $summary['margin_kotor'] = $summary['total_pemasukan'] > 0
            ? round(($summary['laba_kotor'] / $summary['total_pemasukan']) * 100, 2) : 0;
        $summary['margin_bersih'] = $summary['total_pemasukan'] > 0
            ? round(($summary['laba_bersih'] / $summary['total_pemasukan']) * 100, 2) : 0;

        // Tren Bulanan Agregat (Semua Waktu)
        $trendDataRaw = LaporanAnalisis::where('user_id', $userId)
            ->orderBy('bulan')
            ->get();

        $trendData = $trendDataRaw->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->bulan)->format('Y-m');
        })->map(function($group) {
            $firstItem = $group->first();
            $date = \Carbon\Carbon::parse($firstItem->bulan);
            
            $total_pemasukan = $group->sum('total_pemasukan');
            $laba_kotor = $group->sum('laba_kotor');
            $laba_bersih = $group->sum('laba_bersih');

            return (object) [
                'thn' => (int) $date->year,
                'bln' => (int) $date->month,
                'bulan' => $date->format('Y-m-01'), 
                'total_pemasukan' => $total_pemasukan,
                'total_hpp' => $group->sum('total_hpp'),
                'total_operasional' => $group->sum('total_operasional'),
                'laba_kotor' => $laba_kotor,
                'laba_bersih' => $laba_bersih,
                'margin_kotor' => $total_pemasukan > 0 ? round(($laba_kotor / $total_pemasukan) * 100, 2) : 0,
                'margin_bersih' => $total_pemasukan > 0 ? round(($laba_bersih / $total_pemasukan) * 100, 2) : 0,
            ];
        })->values();

        // Regresi Linear untuk Prediksi (Forecasting) Bulan Depan
        $prediksi = null;
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        // Buang bulan berjalan dari data training agar tren tidak jatuh akibat data belum lengkap
        $trainingData = $trendData->reject(function($item) use ($currentYear, $currentMonth) {
            return $item->thn == $currentYear && $item->bln == $currentMonth;
        })->values();

        $n = $trainingData->count();
        if ($n > 1) {
            $sumX = 0; $sumX2 = 0;
            $sumY_pem = 0; $sumXY_pem = 0;
            $sumY_laba = 0; $sumXY_laba = 0;

            foreach ($trainingData as $i => $data) {
                $x = $i + 1;
                $sumX += $x;
                $sumX2 += ($x * $x);

                $sumY_pem += $data->total_pemasukan;
                $sumXY_pem += ($x * $data->total_pemasukan);

                $sumY_laba += $data->laba_bersih;
                $sumXY_laba += ($x * $data->laba_bersih);
            }

            $denominator = ($n * $sumX2) - ($sumX * $sumX);
            if ($denominator != 0) {
                $m_pem = (($n * $sumXY_pem) - ($sumX * $sumY_pem)) / $denominator;
                $b_pem = ($sumY_pem - ($m_pem * $sumX)) / $n;
                $pred_pem = ($m_pem * ($n + 1)) + $b_pem;

                $m_laba = (($n * $sumXY_laba) - ($sumX * $sumY_laba)) / $denominator;
                $b_laba = ($sumY_laba - ($m_laba * $sumX)) / $n;
                $pred_laba = ($m_laba * ($n + 1)) + $b_laba;

                $lastDate = \Carbon\Carbon::parse($trainingData->last()->bulan);
                $prediksi = [
                    'pemasukan' => max(0, $pred_pem),
                    'laba_bersih' => $pred_laba, // bisa negatif
                    'label' => 'Prediksi ' . $lastDate->addMonth()->translatedFormat('M Y')
                ];
            }
        }

        return view('pages.analisis', [
            'hasData'      => true,
            'summary'      => $summary,
            'trendData'    => $trendData,
            'trainingData' => $trainingData,
            'prediksi'     => $prediksi,
            'tahun'        => date('Y'),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // LAPORAN BULANAN (PRINTABLE PDF)
    // Menampilkan laporan per bulan yang bisa dicetak / diexport PDF.
    // ──────────────────────────────────────────────────────────────────────
    public function laporan(Request $request)
    {
        $userId = Auth::id();

        // Kumpulkan daftar tahun yang tersedia
        $tahunList = LaporanAnalisis::where('user_id', $userId)
            ->selectRaw('YEAR(bulan) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([date('Y')]);
        }

        // Default filter: bulan & tahun saat ini
        $bulanFilter = $request->input('bulan', date('n'));
        $tahunFilter = $request->input('tahun', date('Y'));

        // Ambil laporan sesuai filter
        $laporanBulan = LaporanAnalisis::where('user_id', $userId)
            ->whereMonth('bulan', $bulanFilter)
            ->whereYear('bulan', $tahunFilter)
            ->orderBy('bulan')
            ->get();

        return view('pages.laporan', compact('laporanBulan', 'bulanFilter', 'tahunFilter', 'tahunList'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // HAPUS LAPORAN
    // Menghapus record dari database dan file Excel dari storage.
    // ──────────────────────────────────────────────────────────────────────
    public function hapus($id)
    {
        $laporan = LaporanAnalisis::where('user_id', Auth::id())->findOrFail($id);

        // Hapus file Excel dari storage jika masih ada
        if (Storage::disk('local')->exists($laporan->file_path)) {
            Storage::disk('local')->delete($laporan->file_path);
        }

        $namaUmkm = $laporan->nama_umkm;
        $laporan->delete();

        return redirect()->route('riwayat')
            ->with('success', 'Laporan analisis "' . $namaUmkm . '" berhasil dihapus.');
    }

}
