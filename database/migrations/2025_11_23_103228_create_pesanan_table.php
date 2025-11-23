<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->bigInteger('total_harga');
            $table->enum('metode_bayar', ['tunai','qris','transfer']);
            $table->enum('status', ['menunggu','diproses','selesai'])->default('menunggu');
            $table->foreignId('kasir_id')->constrained('users')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesanan');
    }
};
