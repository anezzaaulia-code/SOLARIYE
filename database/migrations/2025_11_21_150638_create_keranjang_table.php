<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->increments('keranjang_id');
            
            $table->integer('menu_id')->unsigned();
            $table->integer('jumlah');
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('menu_id')->on('menu')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
