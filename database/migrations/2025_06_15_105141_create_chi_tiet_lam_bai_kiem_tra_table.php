<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietLamBaiKiemTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_lam_bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cau_hoi_bai_kiem_tra');
            $table->unsignedBigInteger('id_ket_qua_bai_kiem_tra');
            $table->string('dap_an_chon', 2);
            $table->boolean('chon_dung');
            
            $table->foreign('id_cau_hoi_bai_kiem_tra')->references('id')->on('cau_hoi_bai_kiem_tra')->onDelete('cascade');
            $table->foreign('id_ket_qua_bai_kiem_tra')->references('id')->on('ket_qua_bai_kiem_tra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_lam_bai_kiem_tra');
    }
}
