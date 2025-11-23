<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stok_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('bahan_id')->constrained('bahan_baku')->cascadeOnDelete();
            $table->integer('stok_awal');
            $table->integer('stok_akhir');
            $table->enum('status_warna', ['aman','menipis','habis']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stok_harian');
    }
};
