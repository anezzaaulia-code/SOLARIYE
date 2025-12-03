<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah tabel bahan_baku memiliki kolom satuan
        if (!Schema::hasColumn('bahan_baku', 'satuan')) {
            Schema::table('bahan_baku', function (Blueprint $table) {
                // Tambahkan kolom jika belum ada
                $table->string('satuan')->default('pcs')->after('nama_bahan'); // sesuaikan 'after' jika perlu
            });
        }
    }

    public function down(): void
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('bahan_baku', 'satuan')) {
                $table->dropColumn('satuan');
            }
        });
    }
};