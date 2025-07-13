<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTienDoHocTapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tien_do_hoc_tap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_thanh_vien');  // sinh viên
            $table->unsignedBigInteger('id_bai_trong_lop');   // bài giảng cụ thể
            $table->boolean('da_hoan_thanh')->default(false);
            $table->timestamp('thoi_gian_hoan_thanh')->nullable();
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('id_thanh_vien')->references('id')->on('thanh_vien_lop')->onDelete('cascade');
            $table->foreign('id_bai_trong_lop')->references('id')->on('bai_trong_lop')->onDelete('cascade');

            // Không cho phép trùng bản ghi
            $table->unique(['id_thanh_vien', 'id_bai_trong_lop']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tien_do_hoc_tap');
    }
}
