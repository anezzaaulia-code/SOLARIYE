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
        $stokTerbaru = $this->stokHarian()->latest('tanggal')->value('stok_akhir');

        if ($stokTerbaru === null) {
            $this->status = 'Belum Ada Stok';
        } elseif ($stokTerbaru <= $this->batas_merah) {
            $this->status = 'Merah'; // Habis
        } elseif ($stokTerbaru <= $this->batas_kuning) {
            $this->status = 'Kuning'; // Menipis
        } else {
            $this->status = 'Hijau'; // Aman
        }

        $this->save();
    }


}
