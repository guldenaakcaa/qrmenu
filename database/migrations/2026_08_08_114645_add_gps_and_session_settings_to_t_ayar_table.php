<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpsAndSessionSettingsToTAyarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_ayar', function (Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('is_gps_check_active')->default(0);
            $table->integer('session_timeout_minutes')->default(120);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_ayar', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'is_gps_check_active', 'session_timeout_minutes']);
        });
    }
}
