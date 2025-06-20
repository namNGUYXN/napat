<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiTrongLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai_trong_lop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lop_hoc_phan');
            $table->unsignedBigInteger('id_bai');

            $table->unique(['id_lop_hoc_phan', 'id_bai']);
            // FK
            $table->foreign('id_lop_hoc_phan')->references('id')->on('lop_hoc_phan')->onDelete('cascade');
            //FK
            $table->foreign('id_bai')->references('id')->on('bai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_trong_lop');
    }
}
