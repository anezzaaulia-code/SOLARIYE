<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'keuangan_id';

    protected $fillable = [
        'tanggal',
        'jenis',
        'sumber',
        'jumlah',
        'keterangan',
    ];
}
