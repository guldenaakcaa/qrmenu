<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsColumnsToTAyarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_ayar', function (Blueprint $table) {
            $table->string('slogan')->nullable();
            $table->string('favicon')->nullable();
            $table->string('telefon')->nullable();
            $table->text('adres')->nullable();
            $table->text('google_map_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('wifi_ssid')->nullable();
            $table->string('wifi_password')->nullable();
            $table->string('karsilama_gorsel')->nullable();
            $table->string('para_birimi')->default('₺');
            $table->integer('kdv_orani')->default(20);
            $table->boolean('menu_durumu')->default(1);
            $table->boolean('coklu_dil_aktif')->default(1);
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
            $table->dropColumn([
                'slogan',
                'favicon',
                'telefon',
                'adres',
                'google_map_url',
                'instagram_url',
                'whatsapp_number',
                'wifi_ssid',
                'wifi_password',
                'karsilama_gorsel',
                'para_birimi',
                'kdv_orani',
                'menu_durumu',
                'coklu_dil_aktif'
            ]);
        });
    }
}
