<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoaiMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon')->default('');
            $table->integer('thu_tu')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loai_menu');
    }
}
