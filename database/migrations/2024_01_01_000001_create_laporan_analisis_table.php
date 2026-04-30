<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel laporan_analisis di MySQL.
     * Data hasil kalkulasi Python disimpan di sini via Eloquent.
     */
    public function up(): void
    {
        Schema::create('laporan_analisis', function (Blueprint $table) {
            $table->id();                                         // ID unik auto-increment
            $table->string('nama_umkm');                         // Nama pelaku UMKM
            $table->string('bulan');                             // Periode (contoh: "Juni 2025")
            $table->string('file_path');                         // Path file Excel tersimpan
            $table->decimal('total_pemasukan',   15, 2)->default(0);
            $table->decimal('total_hpp',         15, 2)->default(0);
            $table->decimal('total_operasional', 15, 2)->default(0);
            $table->decimal('laba_kotor',        15, 2)->default(0);
            $table->decimal('laba_bersih',       15, 2)->default(0);
            $table->decimal('margin_kotor',       5, 2)->default(0); // persentase (%)
            $table->decimal('margin_bersih',      5, 2)->default(0); // persentase (%)
            $table->decimal('break_even',        15, 2)->default(0);
            $table->longText('detail_json')->nullable();         // Detail baris transaksi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_analisis');
    }
};
