<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_item', function (Blueprint $table) {
            $table->id();
            $table->string('id_nota');
            $table->string('kode_barang');
            $table->unsignedSmallInteger('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_item');
    }
}
