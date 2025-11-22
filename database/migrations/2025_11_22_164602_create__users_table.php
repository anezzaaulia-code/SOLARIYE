<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // nama lengkap
            $table->string('username')->unique(); // username login
            $table->string('password');

            // role: super_admin, admin, kasir
            $table->enum('role', ['super_admin', 'admin', 'kasir'])->default('kasir');

            // optional
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);

            $table->rememberToken(); // untuk login
            $table->timestamps();
            $table->softDeletes(); // untuk hapus aman
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
