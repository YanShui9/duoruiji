<?php

namespace Tests\Unit\Models;

use App\Models\Video;
use App\Models\Lecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_video()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create([
            'lecture_id' => $lecture->id,
            'title' => '癌痛药物治疗详解',
            'duration' => 3600,
        ]);

        $this->assertDatabaseHas('videos', [
            'title' => '癌痛药物治疗详解',
            'lecture_id' => $lecture->id,
            'duration' => 3600,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_lecture()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create(['lecture_id' => $lecture->id]);

        $this->assertInstanceOf(Lecture::class, $video->lecture);
        $this->assertEquals($lecture->id, $video->lecture->id);
    }

    /** @test */
    public function it_casts_expert_ids_to_array()
    {
        $video = Video::factory()->create([
            'expert_ids' => [1, 2, 3],
        ]);

        // 重新从数据库获取以测试 cast
        $video = Video::find($video->id);
        $this->assertIsArray($video->expert_ids);
        $this->assertEquals([1, 2, 3], $video->expert_ids);
    }

    /** @test */
    public function it_can_scope_active_videos()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->count(3)->create(['lecture_id' => $lecture->id, 'status' => 1]);
        Video::factory()->count(2)->create(['lecture_id' => $lecture->id, 'status' => 0]);

        $activeVideos = Video::active()->get();

        $this->assertCount(3, $activeVideos);
        $activeVideos->each(function ($video) {
            $this->assertEquals(1, $video->status);
        });
    }

    /** @test */
    public function it_can_scope_ordered_videos()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->create(['lecture_id' => $lecture->id, 'sort_order' => 3, 'id' => 1]);
        Video::factory()->create(['lecture_id' => $lecture->id, 'sort_order' => 1, 'id' => 2]);
        Video::factory()->create(['lecture_id' => $lecture->id, 'sort_order' => 2, 'id' => 3]);

        $orderedVideos = Video::ordered()->get();

        // scopeOrdered 按 id desc 排序
        $this->assertEquals(3, $orderedVideos[0]->id);
        $this->assertEquals(2, $orderedVideos[1]->id);
        $this->assertEquals(1, $orderedVideos[2]->id);
    }

    /** @test */
    public function it_can_get_formatted_duration()
    {
        $video = Video::factory()->create(['duration' => 125]); // 2分5秒
        $this->assertEquals('02:05', $video->formatted_duration);

        $video2 = Video::factory()->create(['duration' => 3661]); // 61分1秒
        $this->assertEquals('61:01', $video2->formatted_duration);

        $video3 = Video::factory()->create(['duration' => 0]);
        $this->assertEquals('00:00', $video3->formatted_duration);
    }

    /** @test */
    public function it_returns_null_for_thumb_cover_when_no_cover()
    {
        $video = Video::factory()->create(['cover_image' => null]);

        $this->assertNull($video->thumb_cover);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $video = new Video();

        $expected = ['lecture_id', 'title', 'description', 'video_url', 'cover_image', 'duration', 'expert_ids', 'sort_order', 'status'];
        $this->assertEquals($expected, $video->getFillable());
    }
}
