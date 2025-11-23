<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga',
        'deskripsi',
        'foto',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }

    public function pesananDetail()
    {
        return $this->hasMany(PesananDetail::class, 'menu_id');
    }
}
