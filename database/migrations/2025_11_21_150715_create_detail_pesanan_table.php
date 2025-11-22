<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('detail_id');
            $table->integer('pesanan_id')->unsigned();
            $table->integer('menu_id')->unsigned();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pesanan_id')
                ->references('pesanan_id')->on('pesanan')
                ->onDelete('cascade');

            $table->foreign('menu_id')
                ->references('menu_id')->on('menu')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
