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
            [
                'title' => '多瑞吉在癌痛治疗中的应用',
                'description' => '本次讲座将详细介绍多瑞吉（芬太尼透皮贴剂）在癌痛治疗中的临床应用，包括适应症、用法用量、不良反应处理等内容。',
                'live_start_time' => Carbon::now()->addDays(3)->setTime(14, 0),
                'live_end_time' => Carbon::now()->addDays(3)->setTime(16, 0),
                'status' => 0,
                'expert_ids' => [1, 2],
                'sort_order' => 1,
            ],
            [
                'title' => '慢性疼痛管理新进展',
                'description' => '探讨慢性疼痛管理的最新研究进展，包括药物治疗、物理治疗、心理干预等多学科综合治疗方法。',
                'live_start_time' => Carbon::now()->subDays(7)->setTime(10, 0),
                'live_end_time' => Carbon::now()->subDays(7)->setTime(12, 0),
                'status' => 2,
                'expert_ids' => [1, 3],
                'sort_order' => 2,
            ],
            [
                'title' => '癌痛规范化治疗培训',
                'description' => '针对基层医疗机构的癌痛规范化治疗培训，提高癌痛诊疗水平。',
                'live_start_time' => Carbon::now()->subDays(14)->setTime(14, 0),
                'live_end_time' => Carbon::now()->subDays(14)->setTime(16, 0),
                'status' => 2,
                'expert_ids' => [2, 3],
                'sort_order' => 3,
            ],
        ];

        foreach ($lectures as $lecture) {
            Lecture::create($lecture);
        }
    }
}
