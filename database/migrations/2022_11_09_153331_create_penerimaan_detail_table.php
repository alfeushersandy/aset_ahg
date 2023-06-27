<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_detail', function (Blueprint $table) {
            $table->id('id_penerimaan_detail');
            $table->unsignedBigInteger('id_penerimaan');
            $table->unsignedBigInteger('id_perencanaan');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah_terima');
            $table->integer('harga_terima');
            $table->integer('subtotal_terima');
            $table->string('status_penerimaan');
            $table->string('penerima');
            $table->unsignedBigInteger('user_input');
            $table->unsignedBigInteger('user_update');
            $table->unsignedBigInteger('user_delete');
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
        Schema::dropIfExists('penerimaan_detail');
    }
}
