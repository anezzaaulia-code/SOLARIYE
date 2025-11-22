<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'bahan_id';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok_awal',
        'stok_akhir'
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
