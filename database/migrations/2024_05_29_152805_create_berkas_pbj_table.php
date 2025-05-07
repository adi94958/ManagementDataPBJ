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
        Schema::create('berkas_pbj', function (Blueprint $table) {
            $table->string('nama_kontrak');
            $table->string('nomor_kontrak')->primary();
            $table->date('tanggal_kontrak_mulai');
            $table->date('tanggal_kontrak_selesai');
            $table->integer('nilai_kontrak_pbj');
            $table->string('nama_vendor');
            $table->string('file_path')->nullable();
            $table->date('tanggal_mulai_pemeliharaan');
            $table->date('tanggal_selesai_pemeliharaan');
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
        Schema::dropIfExists('berkas_pbj');
    }
};
