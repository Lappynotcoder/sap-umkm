<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_produk');
            $table->string('kategori')->nullable();         // Makanan, Minuman, Kerajinan, dll
            $table->decimal('harga_jual', 15, 2);           // harga jual per unit
            $table->decimal('harga_modal', 15, 2)->default(0); // HPP per unit
            $table->integer('stok_saat_ini')->default(0);
            $table->integer('stok_minimum')->default(10);   // ambang batas low stock
            $table->string('satuan')->default('pcs');        // pcs, porsi, kg, liter, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
