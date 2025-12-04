<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan',
        'pelanggan',
        'nomor_wa',
        'kasir_id',
        'kasir_nama',
        'metode_bayar',
        'bayar',
        'total_harga',
        'status',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function detail()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }

    public function keuangan()
    {
        return $this->hasOne(Keuangan::class, 'ref_id')
                    ->where('ref_table', 'pesanan');
    }

    public function getTotalAttribute()
    {
        return $this->detail->sum('subtotal');
    }

}
