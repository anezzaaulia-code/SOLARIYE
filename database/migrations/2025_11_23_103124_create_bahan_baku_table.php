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

            // stok harian & pengurangan stok
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_akhir')->default(0);

            // klasifikasi warna
            $table->enum('status_warna', ['hijau','kuning','merah'])->default('hijau');

            // batas indikator
            $table->integer('batas_kuning')->default(0);
            $table->integer('batas_merah')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bahan_baku');
    }
};
