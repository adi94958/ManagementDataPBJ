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
        Schema::create('tagihan_fho', function (Blueprint $table) {
            $table->string('nomor_kontrak');
            $table->string('nomor_surat_permohonan_fho_vendor')->primary();
            $table->date('tanggal_surat_permohonan_fho_vendor');
            $table->string('nomor_surat_laporan_tindak_lanjut_fho');
            $table->date('tanggal_surat_laporan_tindak_lanjut_fho');
            $table->string('nomor_bapp_pada_fho');
            $table->date('tanggal_bapp_pada_fho');
            $table->string('nomor_bastp_pada_fho');
            $table->date('tanggal_bastp_pada_fho');
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
        Schema::dropIfExists('tagihan_fho');
    }
};
