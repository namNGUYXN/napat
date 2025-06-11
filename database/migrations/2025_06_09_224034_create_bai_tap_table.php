<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiTapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('bai_tap', function (Blueprint $table) {
            $table->id(); // id - primary key, auto increment
            $table->string('tieu_de', 255); // tiêu đề bài tập
            $table->string('slug', 255)->unique(); // slug - unique
            $table->smallInteger('diem_toi_da'); // điểm tối đa
            $table->unsignedBigInteger('id_bai_giang'); // foreign key đến bài giảng
            $table->boolean('is_delete')->default(false); // trạng thái xóa (mặc định false)

            // Khóa ngoại
            //$table->foreign('id_bai_giang')->references('id')->on('bai_giang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_tap');
    }
}
