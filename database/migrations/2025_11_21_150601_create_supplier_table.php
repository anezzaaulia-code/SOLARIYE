<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('nama_supplier');
            $table->string('kontak')->nullable(); // nomor telepon / WA
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kategori')->nullable(); // optional
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('nama_supplier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
