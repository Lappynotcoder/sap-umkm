<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanAnalisis;
use App\Models\User;

class LaporanAnalisisSeeder extends Seeder
{
    /**
     * Seed laporan_analisis dengan data realistis UMKM mikro.
     * Skenario: UMKM kecil dengan omzet bulanan Rp 3-8 juta.
     */
    public function run(): void
    {
        // Buat User 1: Warung Barokah
        $user1 = User::firstOrCreate(
            ['email' => 'admin@sapumkm.test'],
            [
                'name'      => 'Budi Santoso',
                'nama_umkm' => 'Warung Barokah',
                'password'  => bcrypt('password')
            ]
        );

        // Buat User 2: Toko Sejahtera
        $user2 = User::firstOrCreate(
            ['email' => 'user2@sapumkm.test'],
            [
                'name'      => 'Siti Aminah',
                'nama_umkm' => 'Toko Sejahtera',
                'password'  => bcrypt('password')
            ]
        );

        // Bersihkan data lama jika ada supaya tidak duplikat saat di-seed ulang
        LaporanAnalisis::truncate();

        $dataWarung = [];
        $dataToko = [];

        // Generate data from Jan 2024 to May 2026 (29 months)
        for ($year = 2024; $year <= 2026; $year++) {
            $endMonth = ($year == 2026) ? 5 : 12;
            for ($month = 1; $month <= $endMonth; $month++) {
                $date = sprintf('%04d-%02d-15', $year, $month);
                
                // Narrative events
                $isRamadhan = ($year == 2025 && $month == 3) || ($year == 2026 && $month == 2);
                $isLebaran  = ($year == 2025 && $month == 4) || ($year == 2026 && $month == 3);
                $isAkhirTahun = ($month == 12);
                
                // ─── WARUNG BAROKAH ───
                $w_qtyNasi = 200 + rand(-20, 30);
                $w_qtyMinum = 150 + rand(-10, 40);
                $w_qtyGoreng = 200 + rand(-20, 50);
                
                $w_descNasi = 'Penjualan Nasi Bungkus';
                $w_hppBeras = 850000;
                $w_hppGoreng = 280000;
                
                if ($year == 2026 && $month == 1) {
                    // Kerugian Warung: Cuaca Esktrem / Banjir di Januari 2026
                    $w_qtyNasi = 70; 
                    $w_qtyMinum = 50;
                    $w_qtyGoreng = 60;
                    $w_descNasi = 'Penjualan Nasi (Bulan Hujan Lebat)';
                    $w_hppBeras = 800000; // Kulakan tetap banyak, gagal laku
                } elseif ($isRamadhan) {
                    $w_qtyNasi += 80;
                    $w_qtyMinum += 50;
                    $w_descNasi = 'Penjualan Nasi (Buka Puasa)';
                    $w_hppBeras += 250000; // Harga sembako naik saat Ramadhan
                } elseif ($isLebaran) {
                    $w_qtyNasi -= 50; // Tutup seminggu saat mudik
                    $w_qtyMinum -= 30;
                    $w_descNasi = 'Penjualan Nasi (Pasca Lebaran)';
                } elseif ($isAkhirTahun) {
                    $w_qtyNasi += 40; // Liburan akhir tahun
                    $w_descNasi = 'Penjualan Nasi (Musim Liburan)';
                }
                
                // Inflasi tahun 2026
                if ($year == 2026) {
                    $w_hppBeras += 80000;
                    $w_hppGoreng += 30000;
                }

                $detailWarung = [
                    ['kategori'=>'Pemasukan','keterangan'=>$w_descNasi,'kuantitas'=>$w_qtyNasi,'nilai_satuan'=>8000,'nominal'=>$w_qtyNasi*8000],
                    ['kategori'=>'Pemasukan','keterangan'=>'Penjualan Minuman','kuantitas'=>$w_qtyMinum,'nilai_satuan'=>3000,'nominal'=>$w_qtyMinum*3000],
                    ['kategori'=>'Pemasukan','keterangan'=>'Penjualan Gorengan','kuantitas'=>$w_qtyGoreng,'nilai_satuan'=>2000,'nominal'=>$w_qtyGoreng*2000],
                    ['kategori'=>'HPP','keterangan'=>'Beras & Lauk','kuantitas'=>1,'nilai_satuan'=>$w_hppBeras,'nominal'=>$w_hppBeras],
                    ['kategori'=>'HPP','keterangan'=>'Bahan Minuman & Gorengan','kuantitas'=>1,'nilai_satuan'=>$w_hppGoreng,'nominal'=>$w_hppGoreng],
                    ['kategori'=>'Operasional','keterangan'=>'Sewa Gerobak & Tempat','kuantitas'=>1,'nilai_satuan'=>300000,'nominal'=>300000],
                    ['kategori'=>'Operasional','keterangan'=>'Gas LPG 3kg','kuantitas'=>3,'nilai_satuan'=>22000,'nominal'=>66000],
                    ['kategori'=>'Operasional','keterangan'=>'Plastik & Kemasan','kuantitas'=>1,'nilai_satuan'=>75000,'nominal'=>75000],
                ];

                if ($isRamadhan) {
                    $detailWarung[] = ['kategori'=>'Pemasukan','keterangan'=>'Penjualan Takjil Spesial','kuantitas'=>150,'nilai_satuan'=>5000,'nominal'=>750000];
                    $detailWarung[] = ['kategori'=>'HPP','keterangan'=>'Bahan Takjil','kuantitas'=>1,'nilai_satuan'=>350000,'nominal'=>350000];
                }

                $dataWarung[] = [
                    'tanggal' => $date,
                    'detail' => $detailWarung
                ];

                // ─── TOKO SEJAHTERA ───
                $dateToko = sprintf('%04d-%02d-20', $year, $month); // Toko input tanggal 20
                $t_pemSembako = 2800000 + rand(-200000, 300000);
                $t_pemSnack = 850000 + rand(-50000, 100000);
                
                $t_descSembako = 'Penjualan Sembako';
                $t_rugiDesc = null;
                
                if ($year == 2025 && $month == 11) {
                    // Kerugian Toko Sejahtera: November 2025, barang expired
                    $t_pemSembako -= 800000;
                    $t_descSembako = 'Penjualan Sembako (Sepi)';
                    $t_rugiDesc = 'Pemusnahan Sembako Kadaluarsa';
                } elseif ($isRamadhan) {
                    $t_pemSembako += 1200000;
                    $t_descSembako = 'Penjualan Sembako (Bulan Puasa)';
                } elseif ($isLebaran) {
                    $t_pemSembako += 400000;
                    $t_descSembako = 'Penjualan Sembako (Pasca Lebaran)';
                }
                
                // Kalkulasi HPP dengan margin yang fluktuatif
                $marginSembako = $isRamadhan ? 0.87 : 0.85; // margin kegerus saat harga naik
                $t_hppSembako = round($t_pemSembako * $marginSembako);
                $t_hppSnack = round($t_pemSnack * 0.80);

                $detailToko = [
                    ['kategori'=>'Pemasukan','keterangan'=>$t_descSembako,'kuantitas'=>1,'nilai_satuan'=>$t_pemSembako,'nominal'=>$t_pemSembako],
                    ['kategori'=>'Pemasukan','keterangan'=>'Penjualan Snack & Rokok','kuantitas'=>1,'nilai_satuan'=>$t_pemSnack,'nominal'=>$t_pemSnack],
                    ['kategori'=>'HPP','keterangan'=>'Kulakan Sembako','kuantitas'=>1,'nilai_satuan'=>$t_hppSembako,'nominal'=>$t_hppSembako],
                    ['kategori'=>'HPP','keterangan'=>'Kulakan Snack & Rokok','kuantitas'=>1,'nilai_satuan'=>$t_hppSnack,'nominal'=>$t_hppSnack],
                    ['kategori'=>'Operasional','keterangan'=>'Listrik Toko','kuantitas'=>1,'nilai_satuan'=>125000,'nominal'=>125000],
                    ['kategori'=>'Operasional','keterangan'=>'Transport Kulakan','kuantitas'=>3,'nilai_satuan'=>25000,'nominal'=>75000],
                ];

                if ($t_rugiDesc) {
                    // Masukkan HPP fiktif tanpa pemasukan (Rugi barang rusak)
                    $detailToko[] = ['kategori'=>'HPP','keterangan'=>$t_rugiDesc,'kuantitas'=>1,'nilai_satuan'=>950000,'nominal'=>950000];
                }

                if ($isLebaran) {
                    $detailToko[] = ['kategori'=>'Pemasukan','keterangan'=>'Penjualan Parcel Lebaran','kuantitas'=>12,'nilai_satuan'=>80000,'nominal'=>960000];
                    $detailToko[] = ['kategori'=>'HPP','keterangan'=>'Bahan Isi Parcel','kuantitas'=>12,'nilai_satuan'=>60000,'nominal'=>720000];
                }

                $dataToko[] = [
                    'tanggal' => $dateToko,
                    'detail' => $detailToko
                ];
            }
        }

        // Helper function for saving data
        $insertData = function($user, $dataArray) {
            foreach ($dataArray as $entry) {
                $totalPemasukan = $totalHpp = $totalOperasional = 0;

                foreach ($entry['detail'] as $row) {
                    $kat = strtolower($row['kategori']);
                    if ($kat === 'pemasukan')   $totalPemasukan   += $row['nominal'];
                    if ($kat === 'hpp')         $totalHpp         += $row['nominal'];
                    if ($kat === 'operasional') $totalOperasional += $row['nominal'];
                }

                $labaKotor   = $totalPemasukan - $totalHpp;
                $labaBersih  = $labaKotor - $totalOperasional;
                $marginKotor  = $totalPemasukan > 0 ? round(($labaKotor / $totalPemasukan) * 100, 2) : 0;
                $marginBersih = $totalPemasukan > 0 ? round(($labaBersih / $totalPemasukan) * 100, 2) : 0;
                $rasioHpp = $totalPemasukan > 0 ? ($totalHpp / $totalPemasukan) : 0;
                $bep = (1 - $rasioHpp) > 0 ? ($totalOperasional / (1 - $rasioHpp)) : 0;

                LaporanAnalisis::create([
                    'user_id'           => $user->id,
                    'nama_umkm'         => $user->nama_umkm,
                    'bulan'             => $entry['tanggal'],
                    'file_path'         => 'manual_input',
                    'total_pemasukan'   => $totalPemasukan,
                    'total_hpp'         => $totalHpp,
                    'total_operasional' => $totalOperasional,
                    'laba_kotor'        => $labaKotor,
                    'laba_bersih'       => $labaBersih,
                    'margin_kotor'      => $marginKotor,
                    'margin_bersih'     => $marginBersih,
                    'break_even'        => $bep,
                    'detail_json'       => json_encode($entry['detail']),
                ]);
            }
        };

        $insertData($user1, $dataWarung);
        $insertData($user2, $dataToko);

        $this->command->info('✅ Laporan dummy berhasil di-seed (Warung Barokah = user 1, Toko Sejahtera = user 2)');
    }
}
