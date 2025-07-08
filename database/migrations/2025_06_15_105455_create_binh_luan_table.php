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
            $table->unsignedBigInteger('id_binh_luan_cha')->nullable();
            $table->unsignedBigInteger('id_thanh_vien_lop');
            $table->unsignedBigInteger('id_bai_trong_lop');
            $table->timestamp('ngay_tao')->useCurrent();

            // FK
            $table->foreign('id_thanh_vien_lop')->references('id')->on('thanh_vien_lop')->onDelete('cascade');
            // FK
            $table->foreign('id_bai_trong_lop')->references('id')->on('bai_trong_lop')->onDelete('cascade');
            // FK
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
