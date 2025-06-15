<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiGiangLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai_giang_lop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lop_hoc');
            $table->unsignedBigInteger('id_bai_giang');
            $table->unsignedBigInteger('id_chuong');

            $table->foreign('id_lop_hoc')->references('id')->on('lop_hoc')->onDelete('cascade');
            $table->foreign('id_bai_giang')->references('id')->on('bai_giang');
            $table->foreign('id_chuong')->references('id')->on('chuong')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_giang_lop');
    }
}
