<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTbRen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ren', function (Blueprint $table) {
            $table->id('id_perencanaan');
            $table->string('kode_rencana');
            $table->unsignedBigInteger('id_aset');
            $table->unsignedBigInteger('id_lokasi');
            $table->integer('km')->nullable();
            $table->integer('total_item')->nullable();
            $table->integer('total_harga_master')->nullable();
            $table->integer('total_harga_perkiraan')->nullable();
            $table->integer('total_harga_realisasi')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('table_tb_ren');
    }
}
