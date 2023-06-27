<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban', function (Blueprint $table) {
            $table->id('id_ban');
            $table->string('kode_ban');
            $table->string('merek')->nullable();
            $table->integer('kategori_id');
            $table->string('no_seri');
            $table->date('tanggal_pembelian');
            $table->date('tanggal_pemakaian');
            $table->unsignedBigInteger('id_aset')->nullable();
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
        Schema::dropIfExists('ban');
    }
}
