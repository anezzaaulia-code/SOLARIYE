<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'pesanan_id';

    protected $fillable = [
        'nama_pembeli',
        'titik_antar',
        'ongkir',
        'metode_bayar',
        'status_pembayaran',
        'tipe_pesanan',
        'status',
        'total_harga',
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}
