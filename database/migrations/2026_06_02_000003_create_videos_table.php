<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained()->onDelete('cascade');
            $table->string('title', 200)->comment('视频标题');
            $table->text('description')->nullable()->comment('视频简介');
            $table->string('video_url', 500)->comment('视频文件路径');
            $table->string('cover_image')->nullable()->comment('封面图片');
            $table->integer('duration')->default(0)->comment('视频时长（秒）');
            $table->json('expert_ids')->nullable()->comment('关联专家ID列表');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态 0=禁用 1=启用');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
