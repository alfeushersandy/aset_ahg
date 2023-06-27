<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_detail', function (Blueprint $table) {
            $table->id('id_perencanaan_detail');
            $table->unsignedBigInteger('id_perencanaan');
            $table->unsignedBigInteger('id_barang');
            $table->integer('biaya_perkiraan');
            $table->integer('biaya_realisasi')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('subtotal')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('pr_detail');
    }
}
