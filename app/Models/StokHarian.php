<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokHarian extends Model
{
    use HasFactory;

    protected $table = 'stok_harian';

    protected $fillable = [
        'tanggal',
        'bahan_id',
        'stok_awal',
        'stok_akhir',
        'status_warna',
    ];

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
