<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCauHoiBaiTapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cau_hoi_bai_tap', function (Blueprint $table) {
            $table->id(); // id: khóa chính
            $table->text('tieu_de'); // tiêu đề câu hỏi
            $table->text('dap_an_a'); // nội dung đáp án A
            $table->text('dap_an_b'); // nội dung đáp án B
            $table->text('dap_an_c'); // nội dung đáp án C
            $table->text('dap_an_d'); // nội dung đáp án D
            $table->string('dap_an_dung', 2); // đáp án đúng: A/B/C/D
            $table->unsignedBigInteger('id_bai_tap'); // khóa ngoại tới bảng bai_tap

            // Khóa ngoại
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
        Schema::dropIfExists('cau_hoi_bai_tap');
    }
}
