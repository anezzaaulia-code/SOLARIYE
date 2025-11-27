<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok_awal',
        'stok_akhir',
        'status_warna',
        'batas_kuning',
        'batas_merah',
    ];

    // Accessor otomatis untuk status warna
    public function getStatusWarnaAttribute()
    {
        if ($this->stok_akhir <= $this->batas_merah) {
            return 'merah';
        } elseif ($this->stok_akhir <= $this->batas_kuning) {
            return 'kuning';
        } else {
            return 'hijau';
        }
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelianBahan::class, 'bahan_id');
    }

    public function stokHarian()
    {
        return $this->hasMany(StokHarian::class, 'bahan_id');
    }
}
