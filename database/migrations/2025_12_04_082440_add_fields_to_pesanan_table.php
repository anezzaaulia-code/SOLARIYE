<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {

            if (!Schema::hasColumn('pesanan', 'pelanggan')) {
                $table->string('pelanggan')->nullable();
            }

            if (!Schema::hasColumn('pesanan', 'nomor_wa')) {
                $table->string('nomor_wa')->nullable();
            }

            if (!Schema::hasColumn('pesanan', 'kasir_nama')) {
                $table->string('kasir_nama')->nullable();
            }

            if (!Schema::hasColumn('pesanan', 'metode_bayar')) {
                $table->string('metode_bayar')->nullable();
            }

            if (!Schema::hasColumn('pesanan', 'bayar')) {
                $table->integer('bayar')->default(0);
            }

            if (!Schema::hasColumn('pesanan', 'total_harga')) {
                $table->integer('total_harga')->default(0);
            }

        });
    }

    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'pelanggan',
                'nomor_wa',
                'kasir_nama',
                'metode_bayar',
                'bayar',
                'total_harga'
            ]);
        });
    }
};
