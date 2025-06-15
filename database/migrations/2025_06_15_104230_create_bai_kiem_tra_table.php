<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiKiemTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 255);
            $table->string('slug', 255)->unique();
            $table->smallInteger('diem_toi_da');
            $table->timestamp('ngay_bat_dat');
            $table->timestamp('ngay_ket_thuc');
            $table->unsignedBigInteger('id_lop_hoc');
            $table->timestamp('ngay_tao');
            $table->boolean('is_delete')->default(false);

            $table->foreign('id_lop_hoc')->references('id')->on('lop_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_kiem_tra');
    }
}
