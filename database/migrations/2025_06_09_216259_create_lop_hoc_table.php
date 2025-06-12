<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLopHocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lop_hoc', function (Blueprint $table) {
            $table->id(); // id
            $table->string('ma', 20)->unique(); // mã lớp học
            $table->string('ten', 255); // tên lớp học
            $table->string('slug', 255)->unique(); // slug
            $table->string('mo_ta_ngan', 255); // mô tả ngắn
            $table->string('hinh_anh', 255)->nullable(); // hình ảnh (có thể rỗng)
            $table->unsignedInteger('id_hoc_phan');  // FK đến học phần
            $table->unsignedBigInteger('id_giang_vien'); // FK đến người dùng (giảng viên)
            $table->timestamp('ngay_tao')->useCurrent(); // ngày tạo
            $table->boolean('is_delete')->default(false); // trạng thái xóa

            // Khóa ngoại (tuỳ chọn, nếu bảng liên quan đã tồn tại)
            $table->foreign('id_hoc_phan')->references('id')->on('hoc_phan');
            $table->foreign('id_giang_vien')->references('id')->on('nguoi_dung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lop_hoc');
    }
}
