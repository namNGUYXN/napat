<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMucBaiGiangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('muc_bai_giang', function (Blueprint $table) {
            $table->id();                                           // id: PK
            $table->string('ten', 255);                             // tên mục
            $table->string('slug', 255)->unique();                  // slug duy nhất
            $table->string('mo_ta_ngan', 255)->nullable();          // mô tả ngắn 
            $table->string('hinh_anh', 255)->nullable();            // đường dẫn hình
            $table->unsignedBigInteger('id_giang_vien');           // FK giảng viên
            $table->boolean('is_delete')->default(false);          // cờ xóa mềm
            $table->timestamps();

            $table->foreign('id_giang_vien')
                  ->references('id')
                  ->on('nguoi_dung')        
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('muc_bai_giang');
    }
}
