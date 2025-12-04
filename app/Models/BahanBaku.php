<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'batas_kuning',
        'batas_merah',
        'stok'
    ];

    // Relasi ke stok harian
    public function stokHarian()
    {
        return $this->hasMany(StokHarian::class, 'bahan_id');
    }

    public function updateStatus()
    {
        // tidak menyimpan ke database
        return $this->stok <= 0 ? 'Merah' :
            ($this->stok <= $this->batas_merah ? 'Merah' :
            ($this->stok <= $this->batas_kuning ? 'Kuning' : 'Hijau'));
    }
}
