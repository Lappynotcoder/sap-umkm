<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'nama_produk',
        'kategori',
        'harga_jual',
        'harga_modal',
        'stok_saat_ini',
        'stok_minimum',
        'satuan',
        'is_active',
    ];

    protected $casts = [
        'harga_jual'    => 'decimal:2',
        'harga_modal'   => 'decimal:2',
        'stok_saat_ini' => 'integer',
        'stok_minimum'  => 'integer',
        'is_active'     => 'boolean',
    ];

    // ── Relations ──
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('is_active', true)
                     ->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
    }

    // ── Accessors ──
    public function getLabaPerUnitAttribute(): float
    {
        return $this->harga_jual - $this->harga_modal;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stok_saat_ini <= 0;
    }
}
