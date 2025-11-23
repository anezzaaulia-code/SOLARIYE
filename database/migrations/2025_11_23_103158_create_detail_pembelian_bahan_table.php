<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_pembelian_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian_bahan')->cascadeOnDelete();
            $table->foreignId('bahan_id')->constrained('bahan_baku')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('detail_pembelian_bahan');
    }
};
