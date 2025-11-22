<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokHarian extends Model
{
    protected $table = 'stok_harian';
    protected $primaryKey = 'stok_id';

    protected $fillable = [
        'bahan_id',
        'tanggal',
        'stok_awal',
        'stok_akhir',
        'warna_status'
    ];

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
