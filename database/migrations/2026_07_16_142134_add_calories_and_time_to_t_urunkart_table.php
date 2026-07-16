<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaloriesAndTimeToTUrunkartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('t_urunkart', function (Blueprint $table) {
            $table->string('kalori')->nullable();
            $table->string('hazirlanma_suresi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('t_urunkart', function (Blueprint $table) {
            $table->dropColumn(['kalori', 'hazirlanma_suresi']);
        });
    }
}
