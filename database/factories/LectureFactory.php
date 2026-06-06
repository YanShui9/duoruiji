<?php

namespace Database\Factories;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Factories\Factory;

class LectureFactory extends Factory
{
    protected $model = Lecture::class;

    public function definition()
    {
        $categories = array_keys(Lecture::getCategories());

        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement($categories),
            'cover_image' => null,
            'live_url' => $this->faker->url(),
            'live_start_time' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
            'live_end_time' => $this->faker->dateTimeBetween('+8 days', '+14 days'),
            'status' => 0,
            'expert_ids' => [],
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * 直播中状态
     */
    public function live()
    {
        return $this->state([
            'status' => 1,
            'live_start_time' => now()->subHour(),
            'live_end_time' => now()->addHour(),
        ]);
    }

    /**
     * 已结束状态
     */
    public function ended()
    {
        return $this->state([
            'status' => 2,
            'live_start_time' => now()->subDays(2),
            'live_end_time' => now()->subDay(),
        ]);
    }

    /**
     * 未开始（即将开始）
     */
    public function upcoming()
    {
        return $this->state([
            'status' => 0,
            'live_start_time' => now()->addDay(),
            'live_end_time' => now()->addDays(2),
        ]);
    }
}
