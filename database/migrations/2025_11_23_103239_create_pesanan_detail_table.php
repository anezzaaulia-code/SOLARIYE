<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menu')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->bigInteger('harga');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesanan_detail');
    }
};
