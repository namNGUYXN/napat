<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiTapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai_tap', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 100);
            $table->string('slug')->unique();
            $table->smallInteger('diem_toi_da');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->unsignedBigInteger('id_bai');
            $table->boolean('is_delete')->default(false);

            // FK
            $table->foreign('id_bai')->references('id')->on('bai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_tap');
    }
}
