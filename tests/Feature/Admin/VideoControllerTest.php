<?php

namespace Tests\Feature\Admin;

use App\Models\Video;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => 1]);
    }

    /** @test */
    public function guests_cannot_access_video_routes()
    {
        $response = $this->get(route('admin.videos.index'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_users_cannot_access_video_routes()
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($user)->get(route('admin.videos.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_videos_index()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->count(3)->create(['lecture_id' => $lecture->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.videos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('videos');
    }

    /** @test */
    public function admin_can_search_videos()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->create(['lecture_id' => $lecture->id, 'title' => '癌痛药物治疗']);
        Video::factory()->create(['lecture_id' => $lecture->id, 'title' => '疼痛评估方法']);

        $response = $this->actingAs($this->admin)->get(route('admin.videos.index', ['search' => '癌痛']));

        $response->assertStatus(200);
        $response->assertSee('癌痛药物治疗');
        $response->assertDontSee('疼痛评估方法');
    }

    /** @test */
    public function admin_can_filter_videos_by_lecture()
    {
        $lecture1 = Lecture::factory()->create();
        $lecture2 = Lecture::factory()->create();
        Video::factory()->create(['lecture_id' => $lecture1->id]);
        Video::factory()->create(['lecture_id' => $lecture2->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.videos.index', ['lecture_id' => $lecture1->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_videos_by_status()
    {
        $lecture = Lecture::factory()->create();
        Video::factory()->create(['lecture_id' => $lecture->id, 'status' => 1]);
        Video::factory()->create(['lecture_id' => $lecture->id, 'status' => 0]);

        $response = $this->actingAs($this->admin)->get(route('admin.videos.index', ['status' => 1]));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_create_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.videos.create'));

        $response->assertStatus(200);
        $response->assertViewHas(['lectures', 'experts']);
    }

    /** @test */
    public function admin_cannot_store_video_without_required_fields()
    {
        $data = [
            'title' => '', // 必填字段为空
            'lecture_id' => 99999, // 不存在的讲座
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), $data);

        $response->assertSessionHasErrors(['title', 'lecture_id']);
    }

    /** @test */
    public function admin_cannot_store_video_with_invalid_lecture_id()
    {
        $data = [
            'title' => '测试视频',
            'lecture_id' => 99999, // 不存在的讲座
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), $data);

        $response->assertSessionHasErrors('lecture_id');
    }

    /** @test */
    public function admin_cannot_store_video_with_invalid_duration()
    {
        $lecture = Lecture::factory()->create();
        $data = [
            'title' => '测试视频',
            'lecture_id' => $lecture->id,
            'duration' => -100, // 负数时长
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), $data);

        $response->assertSessionHasErrors('duration');
    }

    /** @test */
    public function admin_can_view_edit_form()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create(['lecture_id' => $lecture->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.videos.edit', $video));

        $response->assertStatus(200);
        $response->assertViewHas(['video', 'lectures', 'experts']);
    }

    /** @test */
    public function admin_can_update_video()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create([
            'lecture_id' => $lecture->id,
            'title' => '原标题',
        ]);

        $data = [
            'lecture_id' => $lecture->id,
            'title' => '更新后的标题',
            'description' => '更新后的描述',
            'duration' => 1800,
            'sort_order' => 5,
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.videos.update', $video), $data);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => '更新后的标题',
            'duration' => 1800,
        ]);
    }

    /** @test */
    public function admin_can_destroy_video()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create(['lecture_id' => $lecture->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.videos.destroy', $video));

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    /** @test */
    public function admin_can_toggle_video_status()
    {
        $lecture = Lecture::factory()->create();
        $video = Video::factory()->create([
            'lecture_id' => $lecture->id,
            'status' => 1,
        ]);

        $data = [
            'lecture_id' => $lecture->id,
            'title' => $video->title,
            'status' => 0,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.videos.update', $video), $data);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'status' => 0,
        ]);
    }
}
