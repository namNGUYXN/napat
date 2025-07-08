<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNguoiDungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten', 100);
            $table->string('email', 100)->unique();
            $table->string('sdt', 12)->nullable()->unique();
            $table->string('hinh_anh', 255)->nullable();
            $table->string('mat_khau', 60);
            $table->enum('vai_tro', ['Admin', 'Giảng viên', 'Sinh viên']);
            $table->string('token_remember', 100)->nullable();
            $table->boolean('is_active');
            $table->boolean('is_logged')->default(false);
            $table->timestamp('ngay_tao')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nguoi_dung');
    }
}
