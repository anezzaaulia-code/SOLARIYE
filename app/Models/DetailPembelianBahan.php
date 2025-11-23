<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian_bahan';

    // jika migration menggunakan $table->id('detail_id'), keep primaryKey:
    protected $primaryKey = 'detail_id';

    public $incrementing = true;
    protected $keyType = 'int';

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
