<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota')->unique();
            $table->foreignId('user_id')->nullable()
                  ->constrained('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->integer('diskon')->default(0);
            $table->unsignedBigInteger('bayar')->nullable();
            $table->unsignedBigInteger('kembalian')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'qris', 'transfer', 'other'])->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'batal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['nomor_nota', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
