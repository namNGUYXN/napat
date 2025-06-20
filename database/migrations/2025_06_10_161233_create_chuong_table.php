<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChuongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chuong', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 100);
            $table->string('mo_ta_ngan');
            $table->unsignedBigInteger('id_bai_giang');
            $table->boolean('is_delete')->default(false);
            
            // FK
            $table->foreign('id_bai_giang')->references('id')->on('bai_giang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chuong');
    }
}
