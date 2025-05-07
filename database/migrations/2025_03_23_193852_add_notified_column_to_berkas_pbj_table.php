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
            $table->boolean('notified')->default(false)->after('tanggal_kontrak_selesai');
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
            $table->dropColumn('notified');
        });
    }
};
