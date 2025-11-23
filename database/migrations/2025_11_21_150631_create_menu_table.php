<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                  ->constrained('kategori_menu')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('kode_menu')->nullable()->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->default(0);
            // stok dihapus dari auto-flow; jika tetap ingin melihat stock summary per menu, boleh tambahkan nullable
            $table->string('foto')->nullable();
            $table->enum('status', ['tersedia', 'habis', 'nonaktif'])->default('tersedia');
            $table->enum('tipe', ['makanan', 'minuman', 'snack'])->nullable();
            $table->timestamps();

            $table->index(['nama', 'kode_menu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
