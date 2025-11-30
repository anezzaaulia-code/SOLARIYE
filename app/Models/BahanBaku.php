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
    ];

    // Relasi ke stok harian
    public function stokHarian()
    {
        return $this->hasMany(StokHarian::class, 'bahan_id');
    }

    public function updateStatus()
    {
        if ($this->stok <= 0) {
            $this->status = 'Habis';
        } elseif ($this->stok <= 10) {
            $this->status = 'Menipis';
        } else {
            $this->status = 'Aman';
        }
    }

}
