<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_menu')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama');
            $table->bigInteger('harga');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['tersedia','habis','nonaktif'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu');
    }
};
