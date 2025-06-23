<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiGiangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bai_giang', function (Blueprint $table) {
            $table->id();                   
            $table->string('ten', 100);        
            $table->string('slug')->unique();
            $table->string('mo_ta_ngan')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->unsignedBigInteger('id_giang_vien');
            $table->unsignedInteger('id_hoc_phan');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->boolean('is_delete')->default(false);

            // FK
            $table->foreign('id_giang_vien')->references('id')->on('nguoi_dung');
            // FK
            $table->foreign('id_hoc_phan')->references('id')->on('hoc_phan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bai_giang');
    }
}
