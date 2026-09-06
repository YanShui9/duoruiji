<?php

namespace Tests\Feature\Frontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Lecture;
use App\Models\Expert;
use App\Models\Video;
use Carbon\Carbon;

class LectureVideoLinkTest extends TestCase
{
    /**
     * 测试已结束讲座的"查看往期视频"按钮应链接到该讲座对应的视频页面
     *
     * 当讲座已结束(status=2)时，页面上的"查看往期视频"按钮
     * 应该链接到 /videos?lecture_id={id}，而不是 /videos
     */
    public function test_ended_lecture_shows_video_link_with_lecture_id_filter()
    {
        // 创建一个已结束的讲座
        $lecture = Lecture::create([
            'title' => '测试已结束讲座',
            'description' => '测试描述',
            'category' => 'cancer_pain',
            'live_start_time' => Carbon::now()->subDays(7),
            'live_end_time' => Carbon::now()->subDays(7)->addHours(2),
            'status' => 2, // 已结束
            'expert_ids' => [],
        ]);

        // 访问讲座详情页
        $response = $this->get("/lectures/{$lecture->id}");

        $response->assertStatus(200);

        // 断言页面包含带 lecture_id 参数的视频链接
        $expectedUrl = '/videos?lecture_id=' . $lecture->id;
        $response->assertSee($expectedUrl, false);
    }

    /**
     * 测试视频列表页支持 lecture_id 筛选参数
     *
     * 访问 /videos?lecture_id=X 时，应只显示该讲座下的视频
     */
    public function test_video_index_supports_lecture_id_filter()
    {
        // 创建两个讲座
        $lecture1 = Lecture::create([
            'title' => '讲座A',
            'description' => '描述A',
            'category' => 'cancer_pain',
            'live_start_time' => Carbon::now()->subDays(7),
            'live_end_time' => Carbon::now()->subDays(7)->addHours(2),
            'status' => 2,
            'expert_ids' => [],
        ]);

        $lecture2 = Lecture::create([
            'title' => '讲座B',
            'description' => '描述B',
            'category' => 'pain_management',
            'live_start_time' => Carbon::now()->subDays(14),
            'live_end_time' => Carbon::now()->subDays(14)->addHours(2),
            'status' => 2,
            'expert_ids' => [],
        ]);

        // 为每个讲座创建视频
        Video::create([
            'lecture_id' => $lecture1->id,
            'title' => '讲座A的视频',
            'video_url' => 'videos/files/test.mp4',
            'duration' => 1800,
            'expert_ids' => [],
            'status' => 1,
        ]);

        Video::create([
            'lecture_id' => $lecture2->id,
            'title' => '讲座B的视频',
            'video_url' => 'videos/files/test2.mp4',
            'duration' => 2400,
            'expert_ids' => [],
            'status' => 1,
        ]);

        // 筛选讲座A的视频
        $response = $this->get("/videos?lecture_id={$lecture1->id}");
        $response->assertStatus(200);
        $response->assertSee('讲座A的视频');
        $response->assertDontSee('讲座B的视频');
    }
}
