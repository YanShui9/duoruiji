<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    public function run()
    {
        $videos = [
            // 讲座5 (慢性疼痛管理新进展) 的视频回放
            [
                'lecture_id' => 5,
                'title' => '慢性疼痛的定义与分类',
                'description' => '详细介绍慢性疼痛的定义、分类方法及常见类型。',
                'video_url' => 'videos/files/chronic_pain_01.mp4',
                'duration' => 1800, // 30分钟
                'expert_ids' => [1],
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'lecture_id' => 5,
                'title' => '多瑞吉在慢性疼痛中的应用',
                'description' => '讲解多瑞吉在慢性非癌痛治疗中的适应症和使用方法。',
                'video_url' => 'videos/files/chronic_pain_02.mp4',
                'duration' => 2400, // 40分钟
                'expert_ids' => [3],
                'sort_order' => 2,
                'status' => 1,
            ],

            // 讲座6 (癌痛规范化治疗培训) 的视频回放
            [
                'lecture_id' => 6,
                'title' => '癌痛评估方法详解',
                'description' => '系统介绍NRS、VAS等常用疼痛评估工具的使用方法。',
                'video_url' => 'videos/files/cancer_pain_01.mp4',
                'duration' => 2700, // 45分钟
                'expert_ids' => [2],
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'lecture_id' => 6,
                'title' => '多瑞吉剂量滴定实战',
                'description' => '通过实际病例讲解多瑞吉的剂量滴定方法和注意事项。',
                'video_url' => 'videos/files/cancer_pain_02.mp4',
                'duration' => 3600, // 60分钟
                'expert_ids' => [3],
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'lecture_id' => 6,
                'title' => '癌痛治疗中的不良反应管理',
                'description' => '讲解阿片类药物常见不良反应的预防和处理策略。',
                'video_url' => 'videos/files/cancer_pain_03.mp4',
                'duration' => 2100, // 35分钟
                'expert_ids' => [2, 3],
                'sort_order' => 3,
                'status' => 1,
            ],

            // 讲座7 (多瑞吉在老年患者中的应用) 的视频回放
            [
                'lecture_id' => 7,
                'title' => '老年癌痛患者的特点',
                'description' => '分析老年癌痛患者的特殊性，包括合并症、多重用药等问题。',
                'video_url' => 'videos/files/elderly_01.mp4',
                'duration' => 2400, // 40分钟
                'expert_ids' => [4],
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'lecture_id' => 7,
                'title' => '老年患者多瑞吉使用指南',
                'description' => '针对老年患者的多瑞吉使用建议和剂量调整策略。',
                'video_url' => 'videos/files/elderly_02.mp4',
                'duration' => 1800, // 30分钟
                'expert_ids' => [6],
                'sort_order' => 2,
                'status' => 1,
            ],

            // 讲座8 (疼痛学科建设与发展论坛) 的视频回放
            [
                'lecture_id' => 8,
                'title' => '疼痛学科建设经验分享-北京协和',
                'description' => '分享北京协和医院疼痛科的建设经验和发展历程。',
                'video_url' => 'videos/files/forum_01.mp4',
                'duration' => 3000, // 50分钟
                'expert_ids' => [1],
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'lecture_id' => 8,
                'title' => '疼痛学科建设经验分享-广州中山',
                'description' => '分享广州中山大学附属第一医院疼痛科的建设经验。',
                'video_url' => 'videos/files/forum_02.mp4',
                'duration' => 2700, // 45分钟
                'expert_ids' => [4],
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'lecture_id' => 8,
                'title' => '疼痛学科建设经验分享-湘雅医院',
                'description' => '分享中南大学湘雅医院疼痛科的建设经验。',
                'video_url' => 'videos/files/forum_03.mp4',
                'duration' => 2400, // 40分钟
                'expert_ids' => [6],
                'sort_order' => 3,
                'status' => 1,
            ],
            [
                'lecture_id' => 8,
                'title' => '疼痛学科未来发展趋势',
                'description' => '探讨疼痛学科的未来发展方向和趋势。',
                'video_url' => 'videos/files/forum_04.mp4',
                'duration' => 1500, // 25分钟
                'expert_ids' => [1, 4, 6],
                'sort_order' => 4,
                'status' => 0, // 禁用状态
            ],
        ];

        foreach ($videos as $video) {
            Video::create($video);
        }
    }
}
