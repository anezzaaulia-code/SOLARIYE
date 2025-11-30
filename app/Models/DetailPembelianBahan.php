<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian_bahan';
    protected $fillable = [
        'pembelian_bahan_id',
        'bahan_id',
        'qty',
        'harga_satuan',
        'subtotal'
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahan::class, 'pembelian_bahan_id');
    }

    public function bahan()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
