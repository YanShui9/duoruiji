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
            [
                'name' => '赵医生',
                'title' => '主治医师',
                'hospital' => '广州中山大学附属第一医院',
                'department' => '疼痛科',
                'bio' => '赵医生专注于慢性疼痛的微创介入治疗，在神经病理性疼痛方面有独到见解。',
                'sort_order' => 4,
                'status' => 1,
            ],
            [
                'name' => '刘教授',
                'title' => '主任医师、教授',
                'hospital' => '浙江大学医学院附属邵逸夫医院',
                'department' => '肿瘤内科',
                'bio' => '刘教授在肿瘤支持治疗和姑息医学领域有20年临床经验，擅长复杂癌痛的个体化治疗。',
                'sort_order' => 5,
                'status' => 1,
            ],
            [
                'name' => '陈主任',
                'title' => '副主任医师',
                'hospital' => '中南大学湘雅医院',
                'department' => '疼痛医学科',
                'bio' => '陈主任是国内较早开展疼痛专科的医师之一，在多瑞吉剂量滴定方面经验丰富。',
                'sort_order' => 6,
                'status' => 1,
            ],
            [
                'name' => '周医生',
                'title' => '主治医师',
                'hospital' => '北京协和医院',
                'department' => '麻醉科',
                'bio' => '周医生专注于术后疼痛管理和急性疼痛控制，在多模式镇痛方面有深入研究。',
                'sort_order' => 7,
                'status' => 0, // 禁用状态
            ],
        ];

        foreach ($experts as $expert) {
            Expert::create($expert);
        }
    }
}
