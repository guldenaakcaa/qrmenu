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
        Schema::table('kasas', function (Blueprint $table) {
            $table->decimal('yemek_karti_toplam', 10, 2)->default(0)->after('kredi_karti_toplam');
            $table->decimal('veresiye_toplam', 10, 2)->default(0)->after('yemek_karti_toplam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kasas', function (Blueprint $table) {
            $table->dropColumn(['yemek_karti_toplam', 'veresiye_toplam']);
        });
    }
};
