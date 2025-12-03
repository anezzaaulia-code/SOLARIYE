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
        'stok_akhir',
        'pemakaian',
        'status_warna',
    ];

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
