<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBahan extends Model
{
    use HasFactory;

    protected $table = 'stok_bahan';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok_awal',
        'stok_akhir',
        'pemakaian',
        'batas_minimal',
        'status_warna',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'stok_awal' => 'integer',
        'stok_akhir' => 'integer',
        'pemakaian' => 'integer',
        'batas_minimal' => 'integer',
        'tanggal' => 'date',
    ];

    /**
     * Hitung pemakaian dan tentukan status warna otomatis sebelum menyimpan.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->pemakaian = ($model->stok_awal ?? 0) - ($model->stok_akhir ?? 0);
            // pastikan batas_minimal ada
            $batas = $model->batas_minimal ?? 3;

            if (($model->stok_akhir ?? 0) <= 0) {
                $model->status_warna = 'habis';
            } elseif (($model->stok_akhir ?? 0) <= $batas) {
                $model->status_warna = 'menipis';
            } else {
                $model->status_warna = 'aman';
            }

            // jika tanggal kosong, set ke hari ini
            if (empty($model->tanggal)) {
                $model->tanggal = now()->toDateString();
            }
        });

        static::updating(function ($model) {
            $model->pemakaian = ($model->stok_awal ?? 0) - ($model->stok_akhir ?? 0);
            $batas = $model->batas_minimal ?? 3;

            if (($model->stok_akhir ?? 0) <= 0) {
                $model->status_warna = 'habis';
            } elseif (($model->stok_akhir ?? 0) <= $batas) {
                $model->status_warna = 'menipis';
            } else {
                $model->status_warna = 'aman';
            }
        });
    }
}
