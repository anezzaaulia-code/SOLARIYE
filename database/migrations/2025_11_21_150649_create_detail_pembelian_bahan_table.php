<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian_bahan', function (Blueprint $table) {
            $table->increments('detail_id');
            $table->integer('pembelian_id')->unsigned();
            $table->integer('bahan_id')->unsigned();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('pembelian_id')
                  ->references('pembelian_id')->on('pembelian_bahan')
                  ->onDelete('cascade');

            $table->foreign('bahan_id')
                ->references('bahan_id')->on('bahan_baku')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian_bahan');
    }
};
