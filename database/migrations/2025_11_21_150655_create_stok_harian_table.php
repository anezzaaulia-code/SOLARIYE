<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_harian', function (Blueprint $table) {
            $table->increments('stok_id');
            $table->integer('bahan_id')->unsigned();
            $table->date('tanggal');
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->enum('warna_status', ['hijau', 'kuning', 'merah'])->default('hijau');
            $table->timestamps();

            $table->foreign('bahan_id')
                ->references('bahan_id')->on('bahan_baku')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_harian');
    }
};
