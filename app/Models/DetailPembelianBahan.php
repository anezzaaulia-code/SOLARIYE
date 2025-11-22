<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelianBahan extends Model
{
    protected $table = 'detail_pembelian_bahan';
    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'pembelian_id',
        'bahan_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahan::class, 'pembelian_id');
    }

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
