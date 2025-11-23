<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianSupplier extends Model
{
    use HasFactory;

    protected $table = 'pembelian_supplier';

    protected $fillable = [
        'supplier_id',
        'nama_bahan',
        'jumlah',
        'satuan',
        'harga_satuan',
        'harga_total',
        'tanggal',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'integer',
        'harga_total' => 'integer',
        'tanggal' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function keuangan()
    {
        // one-to-one possible: keuangan.ref_id => pembelian_supplier.id
        return $this->hasOne(Keuangan::class, 'ref_id');
    }
}
