<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAnalisis extends Model
{
    protected $table = 'laporan_analisis';

    protected $fillable = [
        'user_id',
        'nama_umkm',
        'bulan',
        'file_path',
        'total_pemasukan',
        'total_hpp',
        'total_operasional',
        'laba_kotor',
        'laba_bersih',
        'margin_kotor',
        'margin_bersih',
        'break_even',
        'detail_json',
    ];

    protected $casts = [
        'detail_json' => 'array',
    ];
}
