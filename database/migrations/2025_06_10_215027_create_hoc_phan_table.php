<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHocPhanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoc_phan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma', 100)->unique();
            $table->string('ten', 100);
            $table->string('slug', 100)->unique();
            $table->string('mo_ta_ngan', 100)->nullable();
            $table->smallInteger('so_tin_chi');
            $table->unsignedInteger('id_khoa');
            
            $table->foreign('id_khoa')->references('id')->on('khoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoc_phan');
    }
}
