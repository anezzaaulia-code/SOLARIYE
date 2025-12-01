<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokHarian extends Model
{
    protected $table = 'stok_harian';

    protected $fillable = [
        'tanggal',
        'bahan_id',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'status_warna',
    ];

    // Relasi ke bahan baku
    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stok) {
            $stok->stok_akhir = ($stok->stok_awal + $stok->stok_masuk) - $stok->stok_keluar;
        });
    }

}
