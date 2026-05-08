# Sistem Analisis Profit UMKM (SAP-UMKM)

SAP-UMKM adalah platform pencatatan keuangan dan analitik digital yang dirancang untuk membantu para pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) khususnya di wilayah Cilacap. Aplikasi ini berfungsi sebagai buku keuangan digital yang dapat mengelola, menganalisis, dan memvisualisasikan data keuangan mereka secara otomatis.

Berbeda dari sistem tradisional, SAP-UMKM menyediakan visualisasi yang kaya, analisis profitabilitas per transaksi, margin laba, **Semi-Inventory Management**, hingga **Prediksi/Forecasting berbasis Regresi Linear** untuk masa depan bisnis.

---

## Tim Pengembang

- **Firly Nurrohman**
- **Ade Ariansyah Anggoro**
- **Bintang Fajar Jolya Anggara**

Aplikasi ini mendukung pencapaian **Sustainable Development Goals (SDGs)**:

- **Poin 8:** Pekerjaan Layak dan Pertumbuhan Ekonomi

---

## 🔄 Changelog (Update Terbaru)

### `v2.0` — Semi-Inventory Management *(Mei 2026)*

> **UPDATE BESAR** — Sistem berubah dari pencatatan manual murni menjadi Semi-Inventory Management System yang terintegrasi dengan laporan keuangan.

#### ✅ Fitur Baru
- **Modul Produk & Stok** — Halaman CRUD produk lengkap dengan harga jual, harga modal, stok, satuan, dan batas stok minimum.
- **Smart Transaction Form** — Saat input transaksi Pemasukan, user cukup pilih produk dari dropdown → harga otomatis terisi, stok otomatis berkurang. Untuk HPP/Restock, stok otomatis bertambah.
- **Low Stock Alert** — Notifikasi visual di Dashboard jika ada produk dengan stok rendah atau habis. Badge warna: 🟢 Aman, 🟡 Rendah, 🔴 Habis.
- **Produk Terlaris** — Widget di Dashboard menampilkan Top 5 produk dengan penjualan tertinggi.
- **API Produk** — Endpoint JSON `/api/produk` untuk AJAX dropdown di form transaksi.
- **Satuan Kontekstual** — Dropdown satuan dilengkapi contoh produk (misal: `porsi — makanan siap saji`, `gelas — minuman`).

#### ✅ Perubahan Penting
- **Agregasi Bulanan** — Transaksi sekarang digabung per bulan. Input tanggal 1 Mei + tanggal 15 Mei → masuk ke **1 laporan Mei**. BEP, margin, dan laba dihitung dari seluruh data bulan tersebut, bukan per-hari.
- **Redesign Halaman Laporan** — Halaman Laporan dirombak total menjadi format **Laporan Laba Rugi (P&L Statement)** formal: Pendapatan → HPP → Laba Kotor → Beban Operasional → Laba Bersih, dilengkapi rasio keuangan (Margin Kotor, Margin Bersih, BEP).
- **Kolom Tanggal per-Item** — Setiap item transaksi menyimpan tanggal aslinya, sehingga di tabel rincian tetap terlihat tanggal input meskipun data digabung per bulan.
- **Label "Periode"** — Semua tabel riwayat, history, dan dashboard kini menampilkan "Periode: Mei 2025" alih-alih tanggal spesifik.
- **Menu Sidebar Baru** — Ditambahkan menu "Produk & Stok" di sidebar navigasi.
- **Seeder Produk** — Data dummy produk untuk demo (6 produk Warung Barokah + 4 produk Toko Sejahtera), termasuk produk dengan stok rendah dan habis.

#### 🔧 Perbaikan Teknis
- Hapus role `admin` dari seeder (hanya user biasa).
- Mobile responsive card layout untuk semua tabel.
- Optimasi CSS global untuk mobile-cards pattern.

---

### `v1.0` — Rilis Awal

- Pencatatan transaksi manual (Pemasukan, HPP, Operasional).
- Dashboard dengan Chart.js (Bar, Line, Doughnut).
- Forecasting Regresi Linear.
- Manajemen riwayat dengan pagination dan filter tahun.
- Kalkulasi otomatis Laba, Margin, BEP.
- Auth menggunakan Laravel Breeze.

---

## Fitur Utama

| # | Fitur | Keterangan |
|---|-------|------------|
| 1 | **User-Focused Architecture** | Laravel Breeze. Setiap akun = 1 UMKM, privasi data terjamin. |
| 2 | **Pencatatan Keuangan Praktis** | Input Pemasukan, HPP, Operasional langsung di browser. |
| 3 | **Semi-Inventory Management** | `UPDATE` Kelola produk, stok otomatis berkurang/bertambah saat transaksi. |
| 4 | **Smart Transaction Form** | `UPDATE` Pilih produk → harga & keterangan auto-fill, info stok real-time. |
| 5 | **Dashboard Visualisasi Dinamis** | Chart.js interaktif (Bar, Line, Doughnut) + widget Produk Terlaris & Stok Rendah. |
| 6 | **Laporan Laba Rugi Formal** | `UPDATE` Format P&L Statement profesional, siap cetak PDF. |
| 7 | **Agregasi Bulanan Otomatis** | `UPDATE` Transaksi harian digabung per bulan untuk analisis yang akurat. |
| 8 | **Forecasting Prediksi Laba** | Algoritma Regresi Linear dengan isolasi anomali bulan berjalan. |
| 9 | **Low Stock Alert** | `BARU` Peringatan visual produk stok rendah/habis di Dashboard. |
| 10 | **Mobile Responsive** | Seluruh tabel berubah menjadi card layout di layar kecil (≤768px). |

---

## Tech Stack
- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade, Bootstrap 5.3, Chart.js
- **Database:** MySQL / MariaDB
- **Auth:** Laravel Breeze
- **Build Tool:** Vite

---

## Prasyarat Server (Requirements)
- **PHP** ^8.2
- **Composer**
- **Node.js & npm**
- **MySQL** / MariaDB

---

## Cara Instalasi & Menjalankan

1. **Klon Repositori**

    ```bash
    git clone <url-repo-anda>
    cd SAP-umkm
    ```

2. **Install Dependensi Backend & Frontend**

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   Buka file `.env`, atur konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sap-umkm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5. **Jalankan Migrasi Database dan Data Dummy**
   ```bash
   php artisan migrate:fresh --seed
   ```
   > Seeder akan membuat akun user default + data laporan 2.5 tahun + produk contoh dengan variasi stok.

6. **Jalankan Server Development** *(2 terminal terpisah)*

   Terminal 1 — Frontend:
   ```bash
   npm run dev
   ```

   Terminal 2 — Backend:
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi** → `http://localhost:8000`

---

## Struktur Database

```
users
├── id, name, email, nama_umkm, password
│
├── laporan_analisis (1 user : many laporan, 1 bulan = 1 record)
│   ├── bulan, total_pemasukan, total_hpp, total_operasional
│   ├── laba_kotor, laba_bersih, margin_kotor, margin_bersih
│   ├── break_even, detail_json (array transaksi + tanggal per-item)
│   └── file_path
│
└── products (1 user : many products)  ← UPDATE
    ├── nama_produk, kategori, satuan
    ├── harga_jual, harga_modal
    ├── stok_saat_ini, stok_minimum
    └── is_active
```

---

## Panduan Penggunaan Singkat

1. Buka `http://localhost:8000` dan daftar akun baru (wajib isi Nama UMKM).
2. Jika menggunakan Seeder, login dengan: `budi@sapumkm.test` / password: `password`.
3. **(BARU)** Masuk ke menu **Produk & Stok** → tambahkan daftar produk Anda terlebih dahulu.
4. Masuk ke menu **Keuangan** → pilih produk dari dropdown saat input Pemasukan, atau input manual untuk Operasional.
5. Lihat ringkasan di **Dashboard** — perhatikan widget Produk Terlaris dan Stok Rendah.
6. Buka **Laporan** untuk melihat Laporan Laba Rugi bulanan formal (bisa dicetak PDF).
7. Akses **Analisis** untuk forecasting dan visualisasi tren.

---

## Alur Kerja Transaksi

```
[User Input Transaksi Harian]
         │
         ▼
┌─────────────────────────┐
│  Kategori = Pemasukan?  │──Yes──▶ Pilih Produk → Stok ↓ otomatis
│                         │         Harga & keterangan auto-fill
└───────────┬─────────────┘
            │ No
            ▼
┌─────────────────────────┐
│  Kategori = HPP?        │──Yes──▶ Sub: Restock? → Pilih Produk → Stok ↑
│                         │         Sub: Bahan?   → Input manual
└───────────┬─────────────┘
            │ No
            ▼
┌─────────────────────────┐
│  Kategori = Operasional │──Yes──▶ Input manual (sewa, listrik, dll)
└─────────────────────────┘
            │
            ▼
   ┌────────────────────┐
   │ Cek record bulan   │
   │ yang sama ada?     │
   ├──Yes──▶ GABUNGKAN  │ → Append ke detail_json, hitung ulang semua
   └──No───▶ BUAT BARU  │ → Record baru untuk bulan ini
            └────────────┘
```

---

