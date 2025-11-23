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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPembelianBahan::class, 'pembelian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
