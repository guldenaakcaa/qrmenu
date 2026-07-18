<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneCikanToTUrunkartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_urunkart', function (Blueprint $table) {
            $table->boolean('one_cikan')->default(0)->after('UrunResimPath');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_urunkart', function (Blueprint $table) {
            $table->dropColumn('one_cikan');
        });
    }
}
