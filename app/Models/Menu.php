<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'nama_menu',
        'harga',
        'status_menu',
        'gambar',
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'menu_id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'menu_id');
    }
}
