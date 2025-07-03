<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLopHocPhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lop_hoc_phan', function (Blueprint $table) {
            $table->id();
            $table->string('ma', 20)->unique();
            $table->string('ten', 100);
            $table->string('slug')->unique();
            $table->string('mo_ta_ngan')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->unsignedBigInteger('id_giang_vien');
            $table->unsignedBigInteger('id_bai_giang');
            $table->unsignedInteger('id_khoa');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->boolean('is_delete')->default(false);

            // FK
            $table->foreign('id_giang_vien')->references('id')->on('nguoi_dung');
            // FK
            $table->foreign('id_bai_giang')->references('id')->on('bai_giang');
            // FK
            $table->foreign('id_khoa')->references('id')->on('khoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lop_hoc_phan');
    }
}
