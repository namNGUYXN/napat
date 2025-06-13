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
            $table->bigIncrements('id');
            $table->text('noi_dung');
            $table->timestamp('ngay_dang')->useCurrent();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_lop_hoc');
            $table->unsignedBigInteger('id_ban_tin_cha')->nullable();
            $table->boolean('is_delete')->default(false);

            // Foreign keys
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung');
            $table->foreign('id_lop_hoc')->references('id')->on('lop_hoc');
            $table->foreign('id_ban_tin_cha')->references('id')->on('ban_tin')->nullOnDelete();
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
