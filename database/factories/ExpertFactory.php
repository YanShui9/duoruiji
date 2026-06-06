<?php

namespace Database\Factories;

use App\Models\Expert;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertFactory extends Factory
{
    protected $model = Expert::class;

    public function definition()
    {
        $hospitals = ['北京协和医院', '上海瑞金医院', '广州中山医院', '四川华西医院', '浙江邵逸夫医院'];
        $departments = ['疼痛科', '肿瘤科', '麻醉科', '骨科', '神经内科'];
        $titles = ['主任医师', '副主任医师', '主治医师', '教授', '副教授'];

        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->randomElement($titles),
            'hospital' => $this->faker->randomElement($hospitals),
            'department' => $this->faker->randomElement($departments),
            'avatar' => null,
            'bio' => $this->faker->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'status' => 1,
        ];
    }

    /**
     * 禁用状态
     */
    public function inactive()
    {
        return $this->state([
            'status' => 0,
        ]);
    }
}
