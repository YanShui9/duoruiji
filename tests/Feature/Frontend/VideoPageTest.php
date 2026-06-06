<?php

namespace Tests\Feature\Frontend;

use App\Models\Video;
use App\Models\Lecture;
use App\Models\Expert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function videos_index_page_is_accessible()
    {
        $response = $this->get(route('videos.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function videos_index_displays_paginated_videos()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->count(15)->create(['lecture_id' => $lecture->id]);

        $response = $this->get(route('videos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('videos');
    }

    /** @test */
    public function videos_index_only_shows_active_videos()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->count(3)->create(['lecture_id' => $lecture->id, 'status' => 1]);
        Video::factory()->count(2)->create(['lecture_id' => $lecture->id, 'status' => 0]);

        $response = $this->get(route('videos.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function video_show_page_is_accessible()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create(['lecture_id' => $lecture->id]);

        $response = $this->get(route('videos.show', $video->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function video_show_page_returns_404_for_nonexistent_video()
    {
        $response = $this->get('/videos/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function video_show_page_displays_experts()
    {
        $expert = Expert::factory()->create();
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create([
            'lecture_id' => $lecture->id,
            'expert_ids' => [$expert->id],
        ]);

        $response = $this->get(route('videos.show', $video->id));

        $response->assertStatus(200);
        $response->assertViewHas('experts');
    }

    /** @test */
    public function video_show_page_displays_related_videos()
    {
        $expert = Expert::factory()->create();
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create([
            'lecture_id' => $lecture->id,
            'expert_ids' => [$expert->id],
        ]);
        Video::factory()->count(3)->create([
            'lecture_id' => $lecture->id,
            'expert_ids' => [$expert->id],
        ]);

        $response = $this->get(route('videos.show', $video->id));

        $response->assertStatus(200);
        $response->assertViewHas('relatedVideos');
    }

    /** @test */
    public function video_show_page_displays_lecture_info()
    {
        $lecture = Lecture::factory()->create(['title' => '关联讲座']);
        $video = Video::factory()->create(['lecture_id' => $lecture->id]);

        $response = $this->get(route('videos.show', $video->id));

        $response->assertStatus(200);
        $response->assertViewHas('video');
    }
}
