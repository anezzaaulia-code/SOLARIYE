<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';

    protected $fillable = [
        'tanggal',
        'jenis',
        'sumber',
        'nominal',
        'keterangan',
        'ref_id',
        'ref_table',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi khusus pembelian
    public function pembelian()
    {
        return $this->belongsTo(PembelianBahan::class, 'ref_id')
                    ->where('ref_table', 'pembelian');
    }

    // Relasi khusus pesanan (opsional)
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'ref_id')
                    ->where('ref_table', 'pesanan');
    }
}
