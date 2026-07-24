<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasaSiparisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masa_siparis', function (Blueprint $table) {
            $table->id();
            $table->string('masa_isim');
            $table->string('urun_adi');
            $table->integer('adet')->default(1);
            $table->decimal('fiyat', 10, 2)->default(0);
            $table->dateTime('siparis_saati')->nullable();
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
        Schema::dropIfExists('masa_siparis');
    }
}
