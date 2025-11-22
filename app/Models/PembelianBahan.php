<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    protected $table = 'pembelian_bahan';
    protected $primaryKey = 'pembelian_id';

    protected $fillable = [
        'supplier_id',
        'tanggal_pembelian',
        'total_harga',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelianBahan::class, 'pembelian_id');
    }
}
