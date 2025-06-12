<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThanhVienLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thanh_vien_lop', function (Blueprint $table) {
            $table->id(); // id
            $table->unsignedBigInteger('id_lop_hoc'); // FK
            $table->unsignedBigInteger('id_sinh_vien'); // FK
            $table->boolean('is_accept')->default(false); // Trạng thái duyệt
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_lop_hoc')->references('id')->on('lop_hoc')->onDelete('cascade');
            $table->foreign('id_sinh_vien')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thanh_vien_lop');
    }
}
