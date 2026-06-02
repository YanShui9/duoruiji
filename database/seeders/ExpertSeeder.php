<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expert;

class ExpertSeeder extends Seeder
{
    public function run()
    {
        $experts = [
            [
                'name' => '张教授',
                'title' => '主任医师、教授',
                'hospital' => '北京协和医院',
                'department' => '疼痛科',
                'bio' => '张教授是国内疼痛医学领域的知名专家，从事疼痛临床工作30余年，在多瑞吉临床应用方面有丰富经验。',
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'name' => '李主任',
                'title' => '副主任医师',
                'hospital' => '上海瑞金医院',
                'department' => '麻醉科',
                'bio' => '李主任长期从事麻醉与疼痛治疗研究，在癌痛管理领域有深入研究。',
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'name' => '王教授',
                'title' => '主任医师、博士生导师',
                'hospital' => '四川大学华西医院',
                'department' => '肿瘤科',
                'bio' => '王教授在肿瘤姑息治疗和疼痛管理方面有丰富经验，多次参与国家级科研项目。',
                'sort_order' => 3,
                'status' => 1,
            ],
        ];

        foreach ($experts as $expert) {
            Expert::create($expert);
        }
    }
}
