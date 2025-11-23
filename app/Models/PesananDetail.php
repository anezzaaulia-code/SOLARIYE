<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_detail';

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'nama_menu',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'integer',
        'subtotal' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
