<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFkPenjualanItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_item', function (Blueprint $table) {
            $table->foreign('id_nota')->references('id_nota')->on('penjualan');
            $table->foreign('kode_barang')->references('kode')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_item', function (Blueprint $table) {
            //
        });
    }
}
