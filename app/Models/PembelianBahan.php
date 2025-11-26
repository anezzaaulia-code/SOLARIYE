<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan';

    protected $fillable = [
        'supplier_id',
        'tanggal',
        'total_harga',
        'keterangan',
        'created_by',
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relasi ke detail pembelian
    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelianBahan::class, 'pembelian_bahan_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
