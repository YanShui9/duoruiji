<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\Lecture;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'lecture_id' => Lecture::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'video_url' => 'videos/files/' . $this->faker->uuid() . '.mp4',
            'cover_image' => null,
            'duration' => $this->faker->numberBetween(60, 7200),
            'expert_ids' => [],
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
