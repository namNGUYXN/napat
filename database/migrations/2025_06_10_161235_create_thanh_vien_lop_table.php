<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThanhVienLopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thanh_vien_lop', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lop_hoc_phan');
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->boolean('is_accept')->nullable()->default(false);

            $table->unique(['id_lop_hoc_phan', 'id_nguoi_dung']);
            // FK
            $table->foreign('id_lop_hoc_phan')->references('id')->on('lop_hoc_phan')->onDelete('cascade');
            // KF
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thanh_vien_lop');
    }
}
