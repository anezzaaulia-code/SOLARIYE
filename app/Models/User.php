<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relations
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function pembelianSupplier()
    {
        return $this->hasMany(PembelianSupplier::class, 'created_by');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'created_by');
    }

    public function stokLogs()
    {
        return $this->hasMany(StokLog::class);
    }
}
