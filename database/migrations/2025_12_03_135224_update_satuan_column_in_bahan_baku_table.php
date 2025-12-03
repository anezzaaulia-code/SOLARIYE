<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->string('satuan')->nullable(false)->change(); // hapus default
        });
    }

    public function down()
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->string('satuan')->default('pcs')->change(); // rollback
        });
    }
};
