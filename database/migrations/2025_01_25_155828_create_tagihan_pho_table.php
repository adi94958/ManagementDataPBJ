<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_pho', function (Blueprint $table) {
            $table->string('nomor_kontrak');
            $table->string('nomor_ba_pemeriksaan_pekerjaan_pho')->primary();
            $table->date('tanggal_ba_pemeriksaan_pekerjaan_pho');
            $table->string('nomor_ba_serah_terima_pho');
            $table->date('tanggal_ba_serah_terima_pho');
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
        Schema::dropIfExists('tagihan_pho');
    }
};
