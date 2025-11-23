<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'nomor_nota',
        'user_id',
        'total_harga',
        'diskon',
        'bayar',
        'kembalian',
        'metode_pembayaran',
        'status',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'integer',
        'bayar' => 'integer',
        'kembalian' => 'integer',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }
}
