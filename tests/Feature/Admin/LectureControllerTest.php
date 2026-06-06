<?php

namespace Tests\Feature\Admin;

use App\Models\Lecture;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LectureControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => 1]);
    }

    /** @test */
    public function guests_cannot_access_lecture_routes()
    {
        $response = $this->get(route('admin.lectures.index'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_users_cannot_access_lecture_routes()
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($user)->get(route('admin.lectures.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_lectures_index()
    {
        Lecture::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.lectures.index'));

        $response->assertStatus(200);
        $response->assertViewHas('lectures');
    }

    /** @test */
    public function admin_can_search_lectures()
    {
        Lecture::factory()->create(['title' => '癌痛治疗讲座']);
        Lecture::factory()->create(['title' => '疼痛管理讲座']);

        $response = $this->actingAs($this->admin)->get(route('admin.lectures.index', ['search' => '癌痛']));

        $response->assertStatus(200);
        $response->assertSee('癌痛治疗讲座');
        $response->assertDontSee('疼痛管理讲座');
    }

    /** @test */
    public function admin_can_filter_lectures_by_category()
    {
        Lecture::factory()->create(['category' => 'cancer_pain']);
        Lecture::factory()->create(['category' => 'pain_management']);

        $response = $this->actingAs($this->admin)->get(route('admin.lectures.index', ['category' => 'cancer_pain']));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_lectures_by_status()
    {
        Lecture::factory()->live()->create();
        Lecture::factory()->upcoming()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.lectures.index', ['status' => 1]));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_create_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.lectures.create'));

        $response->assertStatus(200);
        $response->assertViewHas('experts');
    }

    /** @test */
    public function admin_can_store_lecture()
    {
        $expert = Expert::factory()->create();

        $data = [
            'title' => '新讲座标题',
            'description' => '讲座描述',
            'category' => 'cancer_pain',
            'live_url' => 'https://example.com/live',
            'live_start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'live_end_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => 0,
            'expert_ids' => [$expert->id],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.lectures.store'), $data);

        $response->assertRedirect(route('admin.lectures.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('lectures', [
            'title' => '新讲座标题',
            'category' => 'cancer_pain',
        ]);
    }

    /** @test */
    public function admin_cannot_store_lecture_with_invalid_category()
    {
        $data = [
            'title' => '测试讲座',
            'category' => 'invalid_category',
            'status' => 0,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.lectures.store'), $data);

        $response->assertSessionHasErrors('category');
    }

    /** @test */
    public function admin_cannot_store_lecture_with_end_time_before_start_time()
    {
        $data = [
            'title' => '测试讲座',
            'status' => 0,
            'live_start_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'live_end_time' => now()->addDay()->format('Y-m-d H:i:s'), // 结束时间早于开始时间
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.lectures.store'), $data);

        $response->assertSessionHasErrors('live_end_time');
    }

    /** @test */
    public function admin_cannot_store_lecture_with_invalid_live_url()
    {
        $data = [
            'title' => '测试讲座',
            'status' => 0,
            'live_url' => 'not-a-valid-url',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.lectures.store'), $data);

        $response->assertSessionHasErrors('live_url');
    }

    /** @test */
    public function admin_can_store_lecture_with_valid_live_url()
    {
        $data = [
            'title' => '测试讲座',
            'status' => 0,
            'live_url' => 'https://example.com/live',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.lectures.store'), $data);

        $response->assertRedirect(route('admin.lectures.index'));
    }

    /** @test */
    public function admin_can_view_edit_form()
    {
        $lecture = Lecture::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.lectures.edit', $lecture));

        $response->assertStatus(200);
        $response->assertViewHas(['lecture', 'experts']);
    }

    /** @test */
    public function admin_can_update_lecture()
    {
        $lecture = Lecture::factory()->create(['title' => '原标题']);

        $data = [
            'title' => '更新后的标题',
            'description' => '更新后的描述',
            'category' => 'pain_management',
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.lectures.update', $lecture), $data);

        $response->assertRedirect(route('admin.lectures.index'));
        $this->assertDatabaseHas('lectures', [
            'id' => $lecture->id,
            'title' => '更新后的标题',
            'category' => 'pain_management',
        ]);
    }

    /** @test */
    public function admin_can_destroy_lecture()
    {
        $lecture = Lecture::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.lectures.destroy', $lecture));

        $response->assertRedirect(route('admin.lectures.index'));
        $this->assertDatabaseMissing('lectures', ['id' => $lecture->id]);
    }

    /** @test */
    public function destroying_lecture_also_deletes_related_videos()
    {
        $lecture = Lecture::factory()->create();
        $video1 = \App\Models\Video::factory()->create(['lecture_id' => $lecture->id]);
        $video2 = \App\Models\Video::factory()->create(['lecture_id' => $lecture->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.lectures.destroy', $lecture));

        $response->assertRedirect(route('admin.lectures.index'));
        $this->assertDatabaseMissing('lectures', ['id' => $lecture->id]);
        $this->assertDatabaseMissing('videos', ['id' => $video1->id]);
        $this->assertDatabaseMissing('videos', ['id' => $video2->id]);
    }
}
