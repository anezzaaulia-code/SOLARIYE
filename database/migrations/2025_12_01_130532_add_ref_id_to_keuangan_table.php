<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu: Kalau kolom 'ref_id' BELUM ada, baru tambahkan.
        if (!Schema::hasColumn('keuangan', 'ref_id')) {
            Schema::table('keuangan', function (Blueprint $table) {
                $table->unsignedBigInteger('ref_id')->nullable()->after('keterangan');
            });
        }
    }

    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            if (Schema::hasColumn('keuangan', 'ref_id')) {
                $table->dropColumn('ref_id');
            }
        });
    }
};