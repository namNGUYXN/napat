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
            $table->string('tieu_de', 255);
            $table->string('slug', 255)->unique();
            $table->text('noi_dung');
            $table->unsignedBigInteger('id_muc_bai_giang'); 
            $table->boolean('is_delete')->default(false);
            $table->timestamps();

            $table->foreign('id_muc_bai_giang')
                  ->references('id')
                  ->on('muc_bai_giang')
                  ->onDelete('cascade');
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
