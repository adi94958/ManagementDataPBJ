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
            $table->date('tanggal_ba_pemeriksaan_pekerjaan_pho')->nullable();
            $table->string('nomor_ba_serah_terima_pho')->nullable();
            $table->date('tanggal_ba_serah_terima_pho')->nullable();
            $table->string('nomor_bapp_pada_pho')->nullable();
            $table->date('tanggal_bapp_pada_pho')->nullable();
            $table->string('nomor_bastp_pada_pho')->nullable();
            $table->date('tanggal_bastp_pada_pho')->nullable();
            $table->string('nomor_permohonan_pho_vendor')->nullable();
            $table->date('tanggal_permohonan_pho_vendor')->nullable();
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
