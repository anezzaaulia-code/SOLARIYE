<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('pembelian_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('supplier')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('nama_bahan')->nullable();
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->nullable();
            $table->unsignedBigInteger('harga_satuan')->nullable();
            $table->unsignedBigInteger('harga_total')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['supplier_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_supplier');
    }
};
