<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan')->nullable(); // kg, liter, butir
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->integer('pemakaian')->virtualAs('stok_awal - stok_akhir')->nullable(); // optional virtual column; if DB doesn't support, compute in app
            $table->integer('batas_minimal')->default(3); // threshold untuk menipis
            $table->enum('status_warna', ['aman', 'menipis', 'habis'])->default('aman');
            $table->date('tanggal')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['nama_bahan', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bahan');
    }
};
