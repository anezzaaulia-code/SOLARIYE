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
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
