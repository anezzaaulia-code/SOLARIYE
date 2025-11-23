<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriMenu extends Model
{
    use HasFactory;

    protected $table = 'kategori_menu';

    protected $fillable = [
        'nama',
        'slug',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategori_id');
    }
}
