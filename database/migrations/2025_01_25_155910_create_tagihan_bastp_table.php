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
        Schema::create('tagihan_bastp', function (Blueprint $table) {
            $table->string('nomor_kontrak');
            $table->string('nomor_permohonan_bastp');
            $table->date('tanggal_permohonan_bastp');
            $table->string('nomor_bastp')->primary();
            $table->date('tanggal_bastp');
            $table->string('jumlah_bayar_termin_1_bastp');
            $table->integer('jangka_waktu_pemeliharaan_bastp');
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
        Schema::dropIfExists('tagihan_bastp');
    }
};
