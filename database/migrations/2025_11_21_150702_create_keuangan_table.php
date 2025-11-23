<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->enum('sumber', ['penjualan', 'supplier', 'lainnya'])->default('lainnya');
            $table->unsignedBigInteger('nominal')->default(0);
            $table->string('keterangan')->nullable();
            $table->foreignId('ref_id')->nullable(); // optional reference (pesanan id / pembelian_supplier id)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['tanggal','jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
