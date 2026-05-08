# 📘 Panduan Penggunaan SAP-UMKM
### Untuk Pelaku UMKM — Dari Nol Sampai Paham

---

## Apa Itu SAP-UMKM?

SAP-UMKM adalah **buku kas digital** untuk usaha kecil Anda. Bayangkan Anda punya buku catatan keuangan yang bisa:
- Otomatis menghitung **laba** dan **rugi**
- Mengingatkan kalau **stok barang** hampir habis
- Membuat **laporan keuangan formal** yang siap cetak
- Memberikan **prediksi** penghasilan bulan depan

Anda cukup memasukkan data jualan dan pengeluaran harian — sisanya biar sistem yang hitung.

---

## Langkah Awal: Daftar & Login

### 1. Buat Akun
Buka website SAP-UMKM, klik **Daftar**, lalu isi:

| Field | Contoh | Keterangan |
|-------|--------|------------|
| Nama Lengkap | Budi Santoso | Nama Anda sebagai pemilik |
| Email | budi@email.com | Untuk login |
| Nama UMKM | Warung Barokah | Nama usaha Anda, akan muncul di laporan |
| Password | ••••••••• | Minimal 8 karakter |

> **Penting:** Nama UMKM yang Anda isi akan tampil di kop surat laporan keuangan. Pastikan penulisannya benar.

### 2. Login
Setelah daftar, masuk menggunakan email dan password yang sudah dibuat.

---

## Menu-Menu di SAP-UMKM

Setelah login, Anda akan melihat **sidebar** (menu samping kiri) berisi 7 menu:

```
📊 Dashboard       → Ringkasan bisnis Anda
📝 Keuangan        → Input transaksi harian
📦 Produk & Stok   → Kelola daftar produk & pantau stok
📄 Laporan         → Laporan Laba Rugi bulanan (bisa cetak)
📈 Analisis        → Grafik & prediksi keuangan
🕐 Riwayat         → Histori semua laporan bulanan
⚙️ Pengaturan      → Ubah profil & password
```

---

## Langkah 1: Daftarkan Produk Anda

**Menu: Produk & Stok**

Sebelum mulai mencatat transaksi, daftarkan dulu produk-produk yang Anda jual. Ini supaya nanti saat input pemasukan, Anda tinggal **pilih dari daftar** — tidak perlu ketik ulang.

### Cara Menambah Produk:
1. Klik menu **Produk & Stok**
2. Klik tombol **+ Tambah Produk**
3. Isi data produk:

| Field | Contoh | Penjelasan |
|-------|--------|------------|
| Nama Produk | Nasi Bungkus | Nama barang yang Anda jual |
| Kategori | Makanan | Kelompok produk |
| Harga Jual | 8.000 | Harga saat dijual ke pembeli |
| Harga Modal | 5.000 | Harga beli/biaya produksi (opsional) |
| Stok Awal | 100 | Jumlah stok saat ini |
| Stok Minimum | 10 | Batas bawah, akan muncul peringatan jika stok di bawah ini |
| Satuan | porsi | Satuan hitung (porsi, pcs, gelas, kg, dll) |

4. Klik **Simpan**

### Indikator Stok:
Setelah produk didaftarkan, stok akan ditandai warna:
- 🟢 **Hijau** = Stok aman (di atas batas minimum)
- 🟡 **Kuning** = Stok menipis (mendekati atau sama dengan batas minimum)
- 🔴 **Merah** = Stok habis (0)

---

## Langkah 2: Catat Transaksi Harian

**Menu: Keuangan**

Ini adalah inti dari aplikasi — tempat Anda mencatat **semua uang masuk dan keluar**.

### Cara Input Transaksi:
1. Klik menu **Keuangan**
2. Pilih **tanggal** transaksi (hari ini atau tanggal lain di bulan yang sama)
3. Isi baris-baris transaksi:

### Ada 3 Jenis Kategori:

#### 🟢 Pemasukan (Uang Masuk)
Ini untuk mencatat **penjualan produk**.

**Cara cepat:** Pilih produk dari dropdown → harga otomatis terisi, stok otomatis berkurang.

| Contoh | Keterangan |
|--------|------------|
| Jual 50 porsi Nasi Bungkus | Pilih produk "Nasi Bungkus", isi Qty: 50 → Total: Rp 400.000 |
| Jual 30 gelas Es Teh | Pilih produk "Es Teh Manis", isi Qty: 30 → Total: Rp 90.000 |

#### 🔵 HPP — Harga Pokok Penjualan (Biaya Bahan)
Ini untuk mencatat **biaya bahan baku** yang digunakan untuk membuat produk.

Ada 2 sub-jenis:
- **Bahan Baku** → Beli bahan mentah (beras, sayur, bumbu). Input manual.
- **Restock** → Beli stok produk jadi. Pilih produk → stok otomatis bertambah.

| Contoh | Keterangan |
|--------|------------|
| Beli beras 25kg | Kategori: HPP, Sub: Bahan Baku, Rp 350.000 |
| Restock Gorengan 200 pcs | Kategori: HPP, Sub: Restock, pilih produk → stok +200 |

#### 🟡 Operasional (Biaya Rutin)
Biaya yang tidak langsung terkait produk, tapi tetap keluar setiap bulan.

| Contoh | Keterangan |
|--------|------------|
| Sewa tempat | Rp 300.000/bulan |
| Gas LPG 3kg × 3 | Rp 66.000 |
| Plastik & kemasan | Rp 75.000 |
| Listrik | Rp 150.000 |

### 4. Klik **Analisa Sekarang**

Setelah submit, sistem akan:
- ✅ Menghitung laba/rugi otomatis
- ✅ Mengurangi/menambah stok produk (jika terkait produk)
- ✅ **Menggabungkan** dengan data bulan yang sama (jika sudah ada transaksi sebelumnya di bulan itu)

> **Catatan Penting:** Anda boleh input berkali-kali dalam sebulan. Sistem otomatis menggabungkan semua transaksi menjadi **1 laporan per bulan**. Jadi jangan khawatir kalau hari ini input penjualan, besok input belanja bahan — semuanya masuk ke laporan bulan yang sama.

---

## Langkah 3: Lihat Hasil di Dashboard

**Menu: Dashboard**

Setelah input transaksi, Anda akan diarahkan ke Dashboard yang menampilkan:

```
┌──────────────────────────────────────────────────┐
│  Total Pemasukan    Total HPP    Biaya Operasional│
│  Rp 2.624.000      Rp 1.130.000   Rp 441.000    │
├──────────────────────────────────────────────────┤
│  Laba Kotor         Laba Bersih   Break Even     │
│  Rp 1.494.000      Rp 1.053.000   Rp 774.554    │
└──────────────────────────────────────────────────┘
```

### Penjelasan Angka-Angka:

| Istilah | Artinya (Bahasa Sederhana) |
|---------|---------------------------|
| **Total Pemasukan** | Semua uang yang masuk dari jualan |
| **Total HPP** | Semua biaya bahan baku & restock |
| **Biaya Operasional** | Biaya rutin (sewa, listrik, gas, dll) |
| **Laba Kotor** | Pemasukan dikurangi biaya bahan (HPP) |
| **Laba Bersih** | Uang yang benar-benar jadi untung setelah semua biaya dikurangi |
| **Break Even Point** | Jumlah penjualan minimal supaya tidak rugi |

### Widget Tambahan:
- 🏆 **Produk Terlaris** — 5 produk yang paling banyak terjual
- ⚠️ **Stok Rendah** — Produk yang stoknya menipis atau habis

Dashboard juga menampilkan:
- 📊 **Grafik batang** pemasukan vs pengeluaran
- 🍩 **Grafik donat** komposisi biaya
- 📈 **Grafik garis** tren laba per bulan

---

## Langkah 4: Cetak Laporan Keuangan

**Menu: Laporan**

Ini adalah fitur untuk melihat dan mencetak **Laporan Laba Rugi** formal — seperti dokumen yang bisa Anda serahkan ke bank, investor, atau untuk arsip pribadi.

### Cara Menggunakan:
1. Klik menu **Laporan**
2. Pilih **Bulan** dan **Tahun** yang ingin dilihat
3. Klik **Filter**
4. Laporan akan tampil dalam format resmi:

```
            WARUNG BAROKAH
           Laporan Laba Rugi
           Periode: Mei 2025

PENDAPATAN
    Penjualan Nasi Bungkus             Rp 1.720.000
    Penjualan Minuman                   Rp   534.000
    Penjualan Gorengan                  Rp   370.000
    ─────────────────────────────────────────────────
    Total Pendapatan                    Rp 2.624.000

HARGA POKOK PENJUALAN
    Beras & Lauk                       (Rp   850.000)
    Bahan Minuman & Gorengan           (Rp   280.000)
    ─────────────────────────────────────────────────
    Laba Kotor                          Rp 1.494.000

BEBAN OPERASIONAL
    Sewa Gerobak & Tempat              (Rp   300.000)
    Gas LPG 3kg                        (Rp    66.000)
    Plastik & Kemasan                  (Rp    75.000)

═══════════════════════════════════════════════════
    LABA BERSIH                         Rp 1.053.000
═══════════════════════════════════════════════════
```

5. Klik **Cetak PDF** untuk print (hanya bisa setelah bulan tersebut berakhir)

> Saat dicetak, laporan tampil bersih tanpa menu website — hanya isi laporan + tanda tangan.

---

## Langkah 5: Pantau Perkembangan Bisnis

**Menu: Analisis**

Halaman ini menampilkan **gambaran besar** keuangan usaha Anda sepanjang tahun:

- 📊 **Grafik Pemasukan vs Pengeluaran** per bulan — lihat bulan mana yang paling menguntungkan
- 🍩 **Komposisi Biaya** — seberapa besar porsi HPP, Operasional, dan Laba
- 📈 **Tren Laba** — apakah bisnis Anda naik atau turun dari bulan ke bulan
- 🔮 **Prediksi Laba Bulan Depan** — berdasarkan tren historis menggunakan perhitungan Regresi Linear

Anda bisa navigasi tahun menggunakan tombol `<` dan `>` untuk membandingkan performa tahun ini vs tahun lalu.

---

## Langkah 6: Cek Riwayat

**Menu: Riwayat**

Tabel ringkasan semua laporan bulanan yang pernah Anda buat:

| Periode | Laba Bersih | Margin |
|---------|-------------|--------|
| Mei 2025 | Rp 1.053.000 | 40.13% |
| Apr 2025 | Rp 980.000 | 38.5% |
| Mar 2025 | Rp 1.120.000 | 42.1% |

Klik ikon 👁 untuk melihat detail, atau klik **Lihat Semua** untuk histori lengkap dengan opsi hapus data.

---

## Alur Kerja Lengkap (Ringkasan)

```
Hari 1: Daftar Akun → Isi Nama UMKM
                │
                ▼
Hari 1: Daftarkan Produk di menu "Produk & Stok"
        (Nasi Bungkus, Es Teh, Gorengan, dll)
                │
                ▼
Setiap Hari: Input Transaksi di menu "Keuangan"
             ├─ Jualan hari ini (Pemasukan)
             ├─ Belanja bahan (HPP)
             └─ Bayar sewa/listrik (Operasional)
                │
                ▼
        Sistem otomatis:
        ├─ Kurangi/tambah stok produk
        ├─ Gabungkan ke laporan bulan ini
        └─ Hitung laba, margin, BEP
                │
                ▼
Kapan Saja: Cek Dashboard → Lihat kondisi keuangan real-time
                │
                ▼
Akhir Bulan: Buka "Laporan" → Cetak Laporan Laba Rugi
                │
                ▼
Evaluasi: Buka "Analisis" → Lihat tren & prediksi bulan depan
```

---

## Tips & Trik

### 💡 Input Tidak Harus Setiap Hari
Anda boleh input seminggu sekali atau bahkan sebulan sekali. Yang penting, pilih tanggal yang benar saat input.

### 💡 Satu Bulan = Satu Laporan
Tidak peduli berapa kali Anda input dalam sebulan, semua data otomatis digabung ke satu laporan bulanan.

### 💡 Perhatikan Stok
Cek widget **Stok Rendah** di Dashboard secara rutin. Kalau ada stok yang 🔴 merah (habis), segera restock supaya tidak kehilangan pelanggan.

### 💡 Gunakan Fitur Produk
Jangan input pemasukan secara manual kalau produknya sudah terdaftar. Pilih dari dropdown agar:
- Harga otomatis terisi (tidak salah hitung)
- Stok otomatis berkurang (tidak perlu hitung manual)
- Laporan lebih detail (nama produk tercatat)

### 💡 Cetak Laporan untuk Arsip
Setiap akhir bulan, cetak laporan sebagai PDF untuk dokumentasi. Laporan ini bisa Anda gunakan untuk:
- Pengajuan pinjaman bank/modal usaha
- Evaluasi bisnis bulanan
- Laporan pajak sederhana

---

## Tanya Jawab (FAQ)

**T: Apakah data saya bisa dilihat orang lain?**
> Tidak. Setiap akun hanya bisa melihat data UMKM miliknya sendiri.

**T: Bagaimana kalau saya salah input?**
> Buka menu **Riwayat**, cari laporan bulan tersebut, lalu hapus dan input ulang.

**T: Apakah bisa diakses dari HP?**
> Bisa. Website ini sudah responsive — tampilannya menyesuaikan layar HP.

**T: Apa itu Break Even Point (BEP)?**
> BEP adalah jumlah penjualan minimum supaya bisnis Anda **tidak rugi**. Kalau penjualan Anda di atas BEP, berarti Anda untung.

**T: Apa bedanya Laba Kotor dan Laba Bersih?**
> **Laba Kotor** = Pemasukan − Biaya Bahan (HPP). Ini belum dikurangi biaya operasional.
> **Laba Bersih** = Laba Kotor − Biaya Operasional. Ini uang yang benar-benar Anda kantongi.

**T: Kenapa tombol Cetak PDF abu-abu?**
> Laporan hanya bisa dicetak setelah bulan tersebut berakhir. Ini supaya data yang dicetak sudah lengkap.

---

*Panduan ini dibuat untuk SAP-UMKM v2.0 — Sistem Analisis Profit UMKM*
*© 2026 Politeknik Negeri Cilacap*
