<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- JANGAN LUPA INI

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
    {
        // 1. PERBAIKI KOLOM SUMBER (Yang tadi)
        // Ubah dulu ke varchar biar aman dari error data lama
        DB::statement("ALTER TABLE keuangan MODIFY COLUMN sumber VARCHAR(50)");
        // Update data lama yang tidak valid
        DB::table('keuangan')
            ->whereNotIn('sumber', ['penjualan', 'pembelian_bahan', 'gaji', 'operasional', 'lainnya'])
            ->update(['sumber' => 'lainnya']);
        // Ubah ke ENUM final
        DB::statement("ALTER TABLE keuangan MODIFY COLUMN sumber ENUM('penjualan', 'pembelian_bahan', 'gaji', 'operasional', 'lainnya')");

        // 2. PERBAIKI KOLOM REF_TABLE (Yang error sekarang)
        // Ubah jadi VARCHAR(50) supaya muat nampung kata 'pembelian_bahan'
        DB::statement("ALTER TABLE keuangan MODIFY COLUMN ref_table VARCHAR(50)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opsional: Kembalikan ke enum lama jika di-rollback
        // Sesuaikan isi enum ini dengan kondisi sebelum kamu ubah
        DB::statement("ALTER TABLE keuangan MODIFY COLUMN sumber ENUM('penjualan', 'lainnya')");
    }
};