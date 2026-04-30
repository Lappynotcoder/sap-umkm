# Sistem Analisis Profit UMKM (SAP-UMKM)

SAP-UMKM adalah platform pencatatan keuangan dan analitik digital yang dirancang untuk membantu para pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) khususnya di wilayah Cilacap. Aplikasi ini berfungsi sebagai buku keuangan digital yang dapat mengelola, menganalisis, dan memvisualisasikan data keuangan mereka secara otomatis.

Berbeda dari sistem tradisional, SAP-UMKM menyediakan visualisasi yang kaya, analisis profitabilitas per transaksi, margin laba, hingga **Prediksi/Forecasting berbasis Regresi Linear** untuk masa depan bisnis.

---

## Tim Pengembang
- **Firly Nurrohman**
- **Ade Ariansyah Anggoro**
- **Bintang Fajar Jolya Anggara**

Aplikasi ini mendukung pencapaian **Sustainable Development Goals (SDGs)**:
- **Poin 8:** Pekerjaan Layak dan Pertumbuhan Ekonomi
- **Poin 9:** Industri, Inovasi, dan Infrastruktur

---

## Fitur Utama Terkini
1. **User-Focused Architecture**: Menggunakan Laravel Breeze. Setiap akun terikat pada satu UMKM secara spesifik, sehingga privasi dan isolasi data sangat terjamin.
2. **Pencatatan Keuangan Praktis**: Input data Pemasukan, HPP (Harga Pokok Penjualan), dan Operasional secara langsung (manual) tanpa repot menggunakan spreadsheet eksternal.
3. **Forecasting Prediksi Laba**: Dilengkapi algoritma *Linear Regression* yang mengukur tren historis transaksi untuk memprediksi Pemasukan dan Laba Bersih di bulan berikutnya.
4. **Dashboard Visualisasi Dinamis**: Menampilkan perkembangan bisnis dengan menggunakan Chart.js (Grafik Bar, Line, Doughnut) secara interaktif.
5. **Kalkulasi Otomatis**: Mengkalkulasi Laba Kotor, Laba Bersih, Margin Keuntungan, serta Break Even Point (BEP) secara instan.

---

## Prasyarat Server (Requirements)
Pastikan sistem Anda memenuhi kebutuhan minimum berikut:
- **PHP** ^8.2
- **Composer** (untuk instalasi dependensi backend)
- **Node.js & npm** (untuk kompilasi frontend Vite)
- **MySQL** / MariaDB (untuk database relasional)

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
   Salin file konfigurasi bawaan Laravel.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env`, lalu atur konfigurasi database Anda di bagian `DB_*`:
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
   Untuk langsung melihat visualisasi dengan data yang sudah terisi (dummy), jalankan migrasi beserta *Seeder*-nya.
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Seeder ini akan otomatis membuat 2 akun pengguna (Warung Barokah & Toko Sejahtera) lengkap dengan tren data 1.5 tahun ke belakang.*

6. **Jalankan Server Development**
   Anda membutuhkan 2 terminal terpisah:
   
   Terminal 1 (untuk mem-build asset UI & Chart):
   ```bash
   npm run dev
   ```

   Terminal 2 (untuk menjalankan web server PHP):
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi**
   Aplikasi sekarang dapat diakses melalui browser di `http://localhost:8000`.

---

## Cara Menggunakan
1. Masuk ke `http://localhost:8000` dan daftar sebagai akun baru (Anda wajib memasukkan Nama UMKM Anda).
2. Jika menggunakan Seeder, Anda bisa login menggunakan akun admin default: `admin@sapumkm.test` / password: `password`.
3. Masuk ke halaman **Catat Transaksi** untuk memasukkan data bulanan.
4. Periksa hasilnya di **Riwayat**, di mana Anda bisa melihat "Detail" per bulan.
5. Akses menu **Analisis** untuk melihat grafik komprehensif, komposisi biaya, dan hasil dari algoritma **Prediksi/Forecasting**.

---

&copy; 2026 SAP-UMKM - Politeknik Negeri Cilacap
# sap-umkm
# sap-umkm
