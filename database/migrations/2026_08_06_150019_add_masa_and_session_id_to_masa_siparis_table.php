<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMasaAndSessionIdToMasaSiparisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masa_siparis', function (Blueprint $table) {
            $table->unsignedBigInteger('masa_id')->nullable()->after('masa_isim');
            $table->string('session_id')->nullable()->after('masa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masa_siparis', function (Blueprint $table) {
            $table->dropColumn(['masa_id', 'session_id']);
        });
    }
}
