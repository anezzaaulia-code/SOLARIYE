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
        'kode_menu',
        'nama',
        'deskripsi',
        'harga',
        'foto',
        'status',
        'tipe',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }

    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'menu_id');
    }

    public function stokLogs()
    {
        return $this->hasMany(StokLog::class, 'nama_bahan', 'nama'); // optional mapping depending on implementation
    }
}
