<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Warung Barokah (user 1)
        $user1 = User::where('email', 'budi@sapumkm.test')->first();
        if ($user1) {
            $productsWarung = [
                ['nama_produk' => 'Nasi Bungkus',     'kategori' => 'Makanan',  'harga_jual' => 8000,  'harga_modal' => 4500,  'stok_saat_ini' => 150, 'stok_minimum' => 30, 'satuan' => 'porsi'],
                ['nama_produk' => 'Es Teh Manis',     'kategori' => 'Minuman',  'harga_jual' => 3000,  'harga_modal' => 1000,  'stok_saat_ini' => 200, 'stok_minimum' => 50, 'satuan' => 'gelas'],
                ['nama_produk' => 'Gorengan Campur',   'kategori' => 'Makanan',  'harga_jual' => 2000,  'harga_modal' => 800,   'stok_saat_ini' => 300, 'stok_minimum' => 50, 'satuan' => 'pcs'],
                ['nama_produk' => 'Kopi Hitam',        'kategori' => 'Minuman',  'harga_jual' => 5000,  'harga_modal' => 1500,  'stok_saat_ini' => 100, 'stok_minimum' => 20, 'satuan' => 'gelas'],
                ['nama_produk' => 'Mie Goreng Spesial','kategori' => 'Makanan',  'harga_jual' => 12000, 'harga_modal' => 6000,  'stok_saat_ini' => 8,   'stok_minimum' => 10, 'satuan' => 'porsi'],
                ['nama_produk' => 'Soto Ayam',         'kategori' => 'Makanan',  'harga_jual' => 10000, 'harga_modal' => 5000,  'stok_saat_ini' => 0,   'stok_minimum' => 15, 'satuan' => 'porsi'],
            ];

            foreach ($productsWarung as $p) {
                Product::firstOrCreate(
                    ['user_id' => $user1->id, 'nama_produk' => $p['nama_produk']],
                    $p + ['user_id' => $user1->id]
                );
            }
        }

        // Toko Sejahtera (user 2)
        $user2 = User::where('email', 'user2@sapumkm.test')->first();
        if ($user2) {
            $productsToko = [
                ['nama_produk' => 'Kue Lapis',     'kategori' => 'Makanan',   'harga_jual' => 15000, 'harga_modal' => 7000, 'stok_saat_ini' => 50,  'stok_minimum' => 10, 'satuan' => 'kotak'],
                ['nama_produk' => 'Roti Manis',    'kategori' => 'Makanan',   'harga_jual' => 5000,  'harga_modal' => 2500, 'stok_saat_ini' => 80,  'stok_minimum' => 15, 'satuan' => 'pcs'],
                ['nama_produk' => 'Keripik Tempe', 'kategori' => 'Makanan',   'harga_jual' => 10000, 'harga_modal' => 4000, 'stok_saat_ini' => 5,   'stok_minimum' => 10, 'satuan' => 'bungkus'],
                ['nama_produk' => 'Gula Merah',    'kategori' => 'Sembako',   'harga_jual' => 18000, 'harga_modal' => 12000,'stok_saat_ini' => 0,   'stok_minimum' => 5,  'satuan' => 'kg'],
            ];

            foreach ($productsToko as $p) {
                Product::firstOrCreate(
                    ['user_id' => $user2->id, 'nama_produk' => $p['nama_produk']],
                    $p + ['user_id' => $user2->id]
                );
            }
        }
    }
}
