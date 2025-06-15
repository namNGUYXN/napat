<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinhLuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id();
            $table->text('noi_dung');
            $table->timestamp('ngay_dang')->useCurrent();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_bai_giang');
            $table->unsignedBigInteger('id_lop_hoc');
            $table->unsignedBigInteger('id_binh_luan_cha')->nullable();

            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_bai_giang')->references('id')->on('bai_giang')->onDelete('cascade');
            $table->foreign('id_lop_hoc')->references('id')->on('lop_hoc')->onDelete('cascade');
            $table->foreign('id_binh_luan_cha')->references('id')->on('binh_luan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binh_luan');
    }
}
