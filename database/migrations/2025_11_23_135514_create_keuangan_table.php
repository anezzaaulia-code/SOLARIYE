<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();

            // Tanggal transaksi
            $table->date('tanggal');

            // pemasukan / pengeluaran
            $table->enum('jenis', ['pemasukan','pengeluaran']);

            // sumber transaksi
            $table->enum('sumber', ['penjualan','supplier','lainnya']);

            // nominal uang
            $table->bigInteger('nominal');

            // catatan
            $table->string('keterangan')->nullable();

            // id referensi
            $table->unsignedBigInteger('ref_id')->nullable();

            // table referensi
            $table->enum('ref_table', ['pesanan','pembelian','none'])->default('none');

            // user pembuat
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
