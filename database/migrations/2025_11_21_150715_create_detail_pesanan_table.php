<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')
                  ->constrained('pesanan')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('menu_id')
                  ->constrained('menu')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('nama_menu'); // snapshot nama
            $table->integer('qty')->default(1);
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->timestamps();

            $table->index(['pesanan_id', 'menu_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
    }
};
