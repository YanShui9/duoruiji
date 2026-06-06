<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 添加更多专家（共20个）
        $hospitals = ['北京协和医院', '上海瑞金医院', '四川华西医院', '广州中山医院', '浙江邵逸夫医院', '中南湘雅医院'];
        $departments = ['疼痛科', '肿瘤科', '麻醉科', '骨科', '神经内科'];
        $titles = ['主任医师', '副主任医师', '主治医师', '教授'];

        for ($i = 8; $i <= 20; $i++) {
            Expert::create([
                'name' => "测试专家{$i}",
                'title' => $titles[array_rand($titles)],
                'hospital' => $hospitals[array_rand($hospitals)],
                'department' => $departments[array_rand($departments)],
                'bio' => "这是测试专家{$i}的简介，用于测试分页功能。",
                'status' => $i <= 18 ? 1 : 0,
            ]);
        }

        // 添加更多讲座（共20个）
        for ($i = 10; $i <= 20; $i++) {
            $status = $i <= 14 ? 0 : 2; // 未开始或已结束
            Lecture::create([
                'title' => "测试讲座{$i} - 医学学术分享",
                'description' => "这是测试讲座{$i}的描述内容。",
                'category' => ['cancer_pain', 'pain_management', 'clinical_research', 'academic_conference'][array_rand([0,1,2,3])],
                'live_start_time' => $status == 0 ? Carbon::now()->addDays($i) : Carbon::now()->subDays($i),
                'live_end_time' => $status == 0 ? Carbon::now()->addDays($i)->addHours(2) : Carbon::now()->subDays($i)->addHours(2),
                'status' => $status,
                'expert_ids' => [rand(1, 7), rand(8, 20)],
            ]);
        }

        // 添加更多视频（共20个）
        $lectureIds = Lecture::pluck('id')->toArray();
        for ($i = 12; $i <= 20; $i++) {
            Video::create([
                'lecture_id' => $lectureIds[array_rand($lectureIds)],
                'title' => "测试视频{$i} - 专家讲座回放",
                'description' => "这是测试视频{$i}的描述。",
                'video_url' => "videos/files/test_video_{$i}.mp4",
                'duration' => rand(1200, 5400),
                'expert_ids' => [rand(1, 20)],
                'status' => $i <= 18 ? 1 : 0,
            ]);
        }
    }
}
