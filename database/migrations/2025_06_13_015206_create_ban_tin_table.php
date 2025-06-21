<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanTinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ban_tin', function (Blueprint $table) {
            $table->id();
            $table->text('noi_dung');
            $table->unsignedBigInteger('id_ban_tin_cha')->nullable();
            $table->unsignedBigInteger('id_thanh_vien_lop');
            $table->unsignedBigInteger('id_lop_hoc_phan');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->boolean('is_delete')->default(false);

            // FK
            $table->foreign('id_thanh_vien_lop')->references('id')->on('thanh_vien_lop')->onDelete('cascade');
            // FK
            $table->foreign('id_lop_hoc_phan')->references('id')->on('lop_hoc_phan')->onDelete('cascade');
            // FK
            $table->foreign('id_ban_tin_cha')->references('id')->on('ban_tin')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ban_tin');
    }
}
