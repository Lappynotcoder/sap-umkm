<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\LaporanAnalisis;
use Carbon\Carbon;

class AnalisisControllerTest extends TestCase
{
    // RefreshDatabase memastikan database (SQLite in-memory) di-reset setiap kali test berjalan
    use RefreshDatabase; 

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat user dummy untuk menyimulasikan login
        $this->user = User::factory()->create([
            'name' => 'Ade',
            'nama_umkm' => 'UMKM Cilacap Sejahtera'
        ]);
    }

    /**
     * Skenario 1: Menguji endpoint prosesUpload menghasilkan kalkulasi profitabilitas yang akurat
     */
    public function test_proses_upload_menghitung_profitabilitas_dan_bep_dengan_benar()
    {
        // Simulasi user login
        $this->actingAs($this->user);

        // Payload simulasi (Data dikirim dari frontend)
        // Pemasukan: 10 item @ 100.000 = 1.000.000
        // HPP: 10 item @ 40.000 = 400.000
        // Operasional: 1 item @ 200.000 = 200.000
        $payload = [
            'bulan' => '2026-05-01', // Bulan Mei
            'kategori' => ['Pemasukan', 'HPP', 'Operasional'],
            'keterangan' => ['Penjualan Produk', 'Bahan Baku', 'Listrik Bulanan'],
            'kuantitas' => [10, 10, 1],
            'nilai_satuan' => [100000, 40000, 200000],
            'nominal' => [1000000, 400000, 200000], 
        ];

        // Tembak endpoint POST. 
        // Catatan: Sesuaikan nama route 'upload.proses' dengan yang ada di routes/web.php kamu
        $response = $this->post(route('upload.proses'), $payload); 

        // Validasi database untuk memastikan kalkulasi di Controller benar
        // Nilai Break Even: Operasional / (1 - (HPP/Pemasukan)) -> 200.000 / (1 - 0.4) = 333333.33
        $this->assertDatabaseHas('laporan_analisis', [
            'user_id' => $this->user->id,
            'total_pemasukan' => 1000000,
            'total_hpp' => 400000,
            'total_operasional' => 200000,
            'laba_kotor' => 600000,    // 1M - 400k
            'laba_bersih' => 400000,   // 600k - 200k
            'margin_kotor' => 60.00,   // (600k / 1M) * 100
            'margin_bersih' => 40.00,  // (400k / 1M) * 100
        ]);

        // Cek BEP dengan toleransi pembulatan (karena float)
        $laporan = LaporanAnalisis::where('user_id', $this->user->id)->first();
        $this->assertEqualsWithDelta(333333.33, $laporan->break_even, 0.1);

        // Pastikan setelah sukses, diarahkan ke dashboard
        $response->assertRedirect(route('dashboard.show', $laporan->id));
    }

    /**
     * Skenario 2: Menguji halaman Analisis memproses algoritma Regresi Linear dengan benar
     */
    public function test_halaman_analisis_menghasilkan_prediksi_regresi_linear()
    {
        $this->actingAs($this->user);

        // Buat data dummy 3 bulan berturut-turut untuk membentuk tren linear (Training Data)
        // Bulan 1: Laba Bersih = 400.000
        LaporanAnalisis::create([
            'user_id' => $this->user->id,
            'nama_umkm' => $this->user->nama_umkm,
            'bulan' => '2026-01-01',
            'total_pemasukan' => 1000000,
            'total_hpp' => 500000,
            'total_operasional' => 100000,
            'laba_kotor' => 500000,
            'laba_bersih' => 400000,
            'margin_kotor' => 50,
            'margin_bersih' => 40,
            'break_even' => 200000,
            'file_path' => 'dummy',
            'detail_json' => '[]'
        ]);

        // Bulan 2: Laba Bersih = 700.000 (Naik 300.000)
        LaporanAnalisis::create([
            'user_id' => $this->user->id,
            'nama_umkm' => $this->user->nama_umkm,
            'bulan' => '2026-02-01',
            'total_pemasukan' => 1500000,
            'total_hpp' => 700000,
            'total_operasional' => 100000,
            'laba_kotor' => 800000,
            'laba_bersih' => 700000,
            'margin_kotor' => 53.33,
            'margin_bersih' => 46.67,
            'break_even' => 187500, 
            'file_path' => 'dummy',
            'detail_json' => '[]'
        ]);

        // Bulan 3: Laba Bersih = 1.000.000 (Naik 300.000 konstan)
        LaporanAnalisis::create([
            'user_id' => $this->user->id,
            'nama_umkm' => $this->user->nama_umkm,
            'bulan' => '2026-03-01',
            'total_pemasukan' => 2000000,
            'total_hpp' => 900000,
            'total_operasional' => 100000,
            'laba_kotor' => 1100000,
            'laba_bersih' => 1000000,
            'margin_kotor' => 55,
            'margin_bersih' => 50,
            'break_even' => 181818,
            'file_path' => 'dummy',
            'detail_json' => '[]'
        ]);

        // Akses halaman analisis
        $response = $this->get('/analisis'); // Sesuaikan URI ini dengan routes/web.php kamu
        $response->assertStatus(200);

        // Ambil data variabel $prediksi yang dilempar dari Controller ke View Blade
        $prediksi = $response->viewData('prediksi');

        // Memastikan prediksi tidak null
        $this->assertNotNull($prediksi);

        // Logika Regresi:
        // x = [1, 2, 3], y = [400k, 700k, 1M]. 
        // Tren kemiringan m (slope) teratur adalah 300.000 per bulan.
        // Maka jika memprediksi bulan ke-4 (x = 4), hasilnya pasti 1.300.000.
        $this->assertEquals(1300000, round($prediksi['laba_bersih']));
        
        // Pemasukan naik 500k tiap bulan (1M -> 1.5M -> 2M). Prediksi bulan ke-4 harusnya 2.500.000.
        $this->assertEquals(2500000, round($prediksi['pemasukan']));
    }
}