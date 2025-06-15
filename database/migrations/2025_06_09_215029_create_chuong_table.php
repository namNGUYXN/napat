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
            $table->string('tieu_de');
            $table->string('mo_ta_ngan');
            $table->unsignedInteger('id_hoc_phan');
            
            $table->foreign('id_hoc_phan')->references('id')->on('hoc_phan')->onDelete('cascade');
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
