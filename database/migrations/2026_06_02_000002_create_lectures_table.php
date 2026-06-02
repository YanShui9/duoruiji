<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->comment('讲座标题');
            $table->text('description')->nullable()->comment('讲座简介');
            $table->string('cover_image')->nullable()->comment('封面图片');
            $table->string('live_url', 500)->nullable()->comment('直播链接');
            $table->dateTime('live_start_time')->nullable()->comment('直播开始时间');
            $table->dateTime('live_end_time')->nullable()->comment('直播结束时间');
            $table->tinyInteger('status')->default(0)->comment('状态 0=未开始 1=直播中 2=已结束');
            $table->json('expert_ids')->nullable()->comment('关联专家ID列表');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lectures');
    }
}
