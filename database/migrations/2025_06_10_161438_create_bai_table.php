<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 100);
            $table->string('slug')->unique();
            $table->mediumText('noi_dung');
            $table->unsignedBigInteger('id_chuong');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->integer('thu_tu')->default(1);
            $table->boolean('is_delete')->default(false);

            //FK
            $table->foreign('id_chuong')->references('id')->on('chuong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai');
    }
}
