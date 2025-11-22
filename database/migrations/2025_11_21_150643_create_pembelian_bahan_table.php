<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_bahan', function (Blueprint $table) {
            $table->increments('pembelian_id');
            $table->integer('supplier_id')->unsigned();
            $table->date('tanggal_pembelian');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('supplier_id')->on('supplier')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan');
    }
};
