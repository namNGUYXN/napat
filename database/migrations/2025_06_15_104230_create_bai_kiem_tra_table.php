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
            $table->string('tieu_de', 100);
            $table->string('slug')->unique();
            $table->smallInteger('diem_toi_da');
            $table->timestamp('ngay_bat_dau')->nullable();
            $table->timestamp('ngay_ket_thuc')->nullable();
            $table->boolean('cho_phep_nop_qua_han')->default(false);
            $table->unsignedBigInteger('id_lop_hoc_phan');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->boolean('is_delete')->default(false);

            $table->foreign('id_lop_hoc_phan')->references('id')->on('lop_hoc_phan')->onDelete('cascade');
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
