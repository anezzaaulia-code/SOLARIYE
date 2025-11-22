<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('pesanan_id');
            $table->string('nama_pembeli');
            $table->string('titik_antar')->nullable();
            $table->integer('ongkir')->default(0); // sesuai permintaan
            $table->enum('metode_bayar', ['transfer', 'tunai']);
            $table->enum('status_pembayaran', ['belum', 'lunas'])->default('belum');
            $table->enum('tipe_pesanan', ['online', 'offline'])->default('online');
            $table->enum('status', ['menunggu', 'diproses', 'dikirim', 'selesai'])->default('menunggu');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
