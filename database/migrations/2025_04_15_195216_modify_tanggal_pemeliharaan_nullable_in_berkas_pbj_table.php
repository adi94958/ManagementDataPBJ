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
    Schema::table('berkas_pbj', function (Blueprint $table) {
        $table->date('tanggal_mulai_pemeliharaan')->nullable()->change();
        $table->date('tanggal_selesai_pemeliharaan')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('berkas_pbj', function (Blueprint $table) {
        $table->date('tanggal_mulai_pemeliharaan')->nullable(false)->change();
        $table->date('tanggal_selesai_pemeliharaan')->nullable(false)->change();
    });
}
};