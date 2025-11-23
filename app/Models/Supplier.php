<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'nama_supplier',
        'kontak',
        'email',
        'alamat',
        'keterangan',
    ];

    public function pembelian()
    {
        return $this->hasMany(PembelianBahan::class, 'supplier_id');
    }
}
