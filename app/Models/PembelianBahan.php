<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    protected $table = 'pembelian_bahan';

    protected $fillable = [
        'supplier_id',
        'tanggal',
        'total_harga',
        'keterangan',
        'created_by'
    ];

    // relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // relasi ke detail pembelian (kalau nanti mau ditambah)
    public function detail()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id');
    }
}
