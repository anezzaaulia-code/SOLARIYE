<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'nama_supplier',
        'nomor_telepon',
        'alamat',
    ];

    public function pembelianBahan()
    {
        return $this->hasMany(PembelianBahan::class, 'supplier_id');
    }
}
