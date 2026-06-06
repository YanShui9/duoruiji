<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lecture;
use Carbon\Carbon;

class LectureSeeder extends Seeder
{
    public function run()
    {
        $lectures = [
            // 即将开始的讲座
            [
                'title' => '多瑞吉在癌痛治疗中的应用',
                'description' => '本次讲座将详细介绍多瑞吉（芬太尼透皮贴剂）在癌痛治疗中的临床应用，包括适应症、用法用量、不良反应处理等内容。',
                'category' => 'cancer_pain',
                'live_start_time' => Carbon::now()->addDays(3)->setTime(14, 0),
                'live_end_time' => Carbon::now()->addDays(3)->setTime(16, 0),
                'status' => 0,
                'expert_ids' => [1, 2],
                'sort_order' => 1,
            ],
            [
                'title' => '疼痛评估与多瑞吉剂量调整',
                'description' => '系统讲解疼痛评估工具的使用方法，以及如何根据评估结果调整多瑞吉剂量，实现精准镇痛。',
                'category' => 'pain_management',
                'live_start_time' => Carbon::now()->addDays(7)->setTime(10, 0),
                'live_end_time' => Carbon::now()->addDays(7)->setTime(12, 0),
                'status' => 0,
                'expert_ids' => [4, 5],
                'sort_order' => 2,
            ],
            [
                'title' => '癌痛规范化诊疗新指南解读',
                'description' => '解读最新发布的癌痛规范化诊疗指南，重点讲解多瑞吉在新版指南中的定位和使用建议。',
                'category' => 'cancer_pain',
                'live_start_time' => Carbon::now()->addDays(14)->setTime(14, 0),
                'live_end_time' => Carbon::now()->addDays(14)->setTime(16, 0),
                'status' => 0,
                'expert_ids' => [1, 3, 6],
                'sort_order' => 3,
            ],

            // 正在直播的讲座
            [
                'title' => '多瑞吉临床应用经验分享',
                'description' => '邀请多位临床专家分享多瑞吉在实际工作中的应用经验，解答常见问题。',
                'category' => 'clinical_research',
                'live_start_time' => Carbon::now()->subHour(),
                'live_end_time' => Carbon::now()->addHours(2),
                'status' => 1,
                'expert_ids' => [2, 5],
                'sort_order' => 4,
                'live_url' => 'https://meeting.example.com/live/12345',
            ],

            // 已结束的讲座
            [
                'title' => '慢性疼痛管理新进展',
                'description' => '探讨慢性疼痛管理的最新研究进展，包括药物治疗、物理治疗、心理干预等多学科综合治疗方法。',
                'category' => 'pain_management',
                'live_start_time' => Carbon::now()->subDays(7)->setTime(10, 0),
                'live_end_time' => Carbon::now()->subDays(7)->setTime(12, 0),
                'status' => 2,
                'expert_ids' => [1, 3],
                'sort_order' => 5,
            ],
            [
                'title' => '癌痛规范化治疗培训',
                'description' => '针对基层医疗机构的癌痛规范化治疗培训，提高癌痛诊疗水平。',
                'category' => 'cancer_pain',
                'live_start_time' => Carbon::now()->subDays(14)->setTime(14, 0),
                'live_end_time' => Carbon::now()->subDays(14)->setTime(16, 0),
                'status' => 2,
                'expert_ids' => [2, 3],
                'sort_order' => 6,
            ],
            [
                'title' => '多瑞吉在老年患者中的应用',
                'description' => '讨论老年癌痛患者的特殊性，以及多瑞吉在老年患者中的安全使用策略。',
                'category' => 'clinical_research',
                'live_start_time' => Carbon::now()->subDays(21)->setTime(14, 0),
                'live_end_time' => Carbon::now()->subDays(21)->setTime(16, 0),
                'status' => 2,
                'expert_ids' => [4, 6],
                'sort_order' => 7,
            ],
            [
                'title' => '疼痛学科建设与发展论坛',
                'description' => '邀请国内知名疼痛科主任，共同探讨疼痛学科的建设与发展。',
                'category' => 'academic_conference',
                'live_start_time' => Carbon::now()->subDays(30)->setTime(9, 0),
                'live_end_time' => Carbon::now()->subDays(30)->setTime(17, 0),
                'status' => 2,
                'expert_ids' => [1, 4, 6],
                'sort_order' => 8,
            ],
        ];

        foreach ($lectures as $lecture) {
            Lecture::create($lecture);
        }
    }
}
