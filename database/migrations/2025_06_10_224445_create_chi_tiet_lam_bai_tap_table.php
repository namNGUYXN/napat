<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietLamBaiTapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_lam_bai_tap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cau_hoi_bai_tap');
            $table->unsignedBigInteger('id_ket_qua_bai_tap');
            $table->string('dap_an_chon', 2);
            $table->boolean('chon_dung');
            
            $table->foreign('id_cau_hoi_bai_tap')->references('id')->on('cau_hoi_bai_tap')->onDelete('cascade');
            $table->foreign('id_ket_qua_bai_tap')->references('id')->on('ket_qua_bai_tap')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_lam_bai_tap');
    }
}
