<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    // gunakan primary key default "id" (konsisten)
    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok_awal',
        'stok_akhir',
        'status_warna',
    ];

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelianBahan::class, 'bahan_id');
    }

    public function stokHarian()
    {
        return $this->hasMany(StokHarian::class, 'bahan_id');
    }
}
