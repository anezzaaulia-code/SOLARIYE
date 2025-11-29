<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan', 50);

            // stok saat ini (bukan stok harian)
            $table->integer('stok')->default(0);

            // batas indikator warna
            $table->integer('batas_kuning')->default(0);
            $table->integer('batas_merah')->default(0);

            // status hasil perhitungan
            $table->enum('status_warna', ['aman','menipis','habis'])->default('aman');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bahan_baku');
    }
};
