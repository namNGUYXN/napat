<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten', 100);
            $table->unsignedInteger('id_loai_menu');
            $table->unsignedInteger('id_menu_cha')->nullable();

            $table->foreign('id_loai_menu')->references('id')->on('loai_menu');
            $table->foreign('id_menu_cha')->references('id')->on('menu');

            $table->string('gia_tri', 255);
            $table->unsignedInteger('thu_tu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
