<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasaIslemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasa_islems', function (Blueprint $table) {
            $table->id();
            $table->date('tarih');
            $table->dateTime('islem_saati')->nullable();
            $table->string('turu')->nullable(); // Nakit / Kredi Kartı vb.
            $table->decimal('tutar', 10, 2)->default(0);
            $table->string('aciklama')->nullable();
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
        Schema::dropIfExists('kasa_islems');
    }
}
