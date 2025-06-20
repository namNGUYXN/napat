<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCauHoiBaiKiemTraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cau_hoi_bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->text('tieu_de');
            $table->text('dap_an_a');
            $table->text('dap_an_b');
            $table->text('dap_an_c');
            $table->text('dap_an_d');
            $table->string('dap_an_dung', 2);
            $table->unsignedBigInteger('id_bai_kiem_tra');

            // FK
            $table->foreign('id_bai_kiem_tra')->references('id')->on('bai_kiem_tra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cau_hoi_bai_kiem_tra');
    }
}
