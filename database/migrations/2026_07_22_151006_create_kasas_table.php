<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasas', function (Blueprint $table) {
            $table->id();
            $table->date('tarih');
            $table->decimal('nakit_toplam', 12, 2)->default(0.00);
            $table->decimal('kredi_karti_toplam', 12, 2)->default(0.00);
            $table->decimal('genel_toplam', 12, 2)->default(0.00);
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
        Schema::dropIfExists('kasas');
    }
}
