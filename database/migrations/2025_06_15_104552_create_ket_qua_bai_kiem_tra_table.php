<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetQuaBaiKiemTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_thanh_vien_lop');
            $table->unsignedBigInteger('id_bai_kiem_tra');
            $table->timestamp('ngay_lam');
            $table->smallInteger('so_cau_dung');

            $table->unique(['id_thanh_vien_lop', 'id_bai_kiem_tra']);
            // FK
            $table->foreign('id_thanh_vien_lop')->references('id')->on('thanh_vien_lop')->onDelete('cascade');
            // FK
            $table->foreign('id_bai_kiem_tra')->references('id')->on('bai_kiem_tra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ket_qua_bai_kiem_tra');
    }
}
