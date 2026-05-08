<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user default
        User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sapumkm.test',
            'nama_umkm' => 'Warung Barokah',
            'password' => bcrypt('password'),
        ]);

        // Seed data dummy laporan analisis
        $this->call(LaporanAnalisisSeeder::class);

        // Seed data produk contoh
        $this->call(ProductSeeder::class);
    }
}
