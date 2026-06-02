<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertsTable extends Migration
{
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('专家姓名');
            $table->string('title', 100)->comment('职称');
            $table->string('hospital', 200)->comment('所属医院');
            $table->string('department', 100)->comment('科室');
            $table->string('avatar')->nullable()->comment('头像路径');
            $table->text('bio')->nullable()->comment('专家简介');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态 0=禁用 1=启用');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('experts');
    }
}
