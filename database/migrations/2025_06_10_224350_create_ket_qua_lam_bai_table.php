<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetQuaLamBaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_bai_tap', function (Blueprint $table) {
            $table->id(); // id: khóa chính
            $table->unsignedBigInteger('id_sinh_vien'); // khóa ngoại đến bảng người dùng (sinh viên)
            $table->unsignedBigInteger('id_bai_tap');   // khóa ngoại đến bảng bài tập
            $table->timestamp('ngay_lam'); // ngày làm bài tập
            $table->smallInteger('so_cau_dung'); // số câu đúng


            // Định nghĩa khóa ngoại
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
        Schema::dropIfExists('ket_qua_lam_bai');
    }
}
