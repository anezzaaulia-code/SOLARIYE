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

    // relasi dinamis
    public function referensi()
    {
        return match ($this->ref_table) {
            'pesanan'   => $this->belongsTo(Pesanan::class, 'ref_id'),
            'pembelian' => $this->belongsTo(PembelianBahan::class, 'ref_id'),
            default     => null,
        };
    }
}
