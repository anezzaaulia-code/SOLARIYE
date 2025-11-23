<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // penting untuk login, biar status dibaca boolean
    protected $casts = [
        'status' => 'boolean',
    ];

    // RELASI
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'kasir_id');
    }

    public function pembelianBahan()
    {
        return $this->hasMany(PembelianBahan::class, 'created_by');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'created_by');
    }
}
