<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetQuaBaiKiemTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sinh_vien');
            $table->unsignedBigInteger('id_bai_tap');
            $table->timestamp('ngay_lam');
            $table->smallInteger('so_cau_dung');

            $table->foreign('id_sinh_vien')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_bai_tap')->references('id')->on('bai_tap')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ket_qua_bai_kiem_tra');
    }
}
