<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('stok_harian', function (Blueprint $table) {
            $table->integer('pemakaian')->default(0);
        });
    }

    public function down()
    {
        Schema::table('stok_harian', function (Blueprint $table) {
            $table->dropColumn('pemakaian');
        });
    }
};
