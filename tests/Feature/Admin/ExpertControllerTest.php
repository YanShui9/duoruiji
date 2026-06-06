<?php

namespace Tests\Feature\Admin;

use App\Models\Expert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => 1]);
    }

    /** @test */
    public function guests_cannot_access_expert_routes()
    {
        $response = $this->get(route('admin.experts.index'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_users_cannot_access_expert_routes()
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($user)->get(route('admin.experts.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_experts_index()
    {
        Expert::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.experts.index'));

        $response->assertStatus(200);
        $response->assertViewHas('experts');
    }

    /** @test */
    public function admin_can_search_experts()
    {
        Expert::factory()->create(['name' => '张医生']);
        Expert::factory()->create(['name' => '李医生']);

        $response = $this->actingAs($this->admin)->get(route('admin.experts.index', ['search' => '张']));

        $response->assertStatus(200);
        $response->assertSee('张医生');
        $response->assertDontSee('李医生');
    }

    /** @test */
    public function admin_can_filter_experts_by_hospital()
    {
        Expert::factory()->create(['hospital' => '北京协和医院']);
        Expert::factory()->create(['hospital' => '上海瑞金医院']);

        $response = $this->actingAs($this->admin)->get(route('admin.experts.index', ['hospital' => '北京协和医院']));

        $response->assertStatus(200);
        // 验证筛选后的专家数据只包含北京协和医院
        $response->assertViewHas('experts', function ($experts) {
            return $experts->every(function ($e) {
                return $e->hospital === '北京协和医院';
            });
        });
    }

    /** @test */
    public function admin_can_filter_experts_by_status()
    {
        Expert::factory()->create(['status' => 1]);
        Expert::factory()->create(['status' => 0]);

        $response = $this->actingAs($this->admin)->get(route('admin.experts.index', ['status' => 1]));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_create_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.experts.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_expert()
    {
        $data = [
            'name' => '新专家',
            'title' => '主任医师',
            'hospital' => '北京协和医院',
            'department' => '疼痛科',
            'bio' => '这是一位专家简介',
            'sort_order' => 10,
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.experts.store'), $data);

        $response->assertRedirect(route('admin.experts.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('experts', ['name' => '新专家']);
    }

    /** @test */
    public function admin_cannot_store_expert_with_invalid_data()
    {
        $data = [
            'name' => '', // 必填字段为空
            'title' => '主任医师',
            'hospital' => '北京协和医院',
            'department' => '疼痛科',
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.experts.store'), $data);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function admin_cannot_store_expert_with_invalid_status()
    {
        $data = [
            'name' => '测试专家',
            'title' => '主任医师',
            'hospital' => '北京协和医院',
            'department' => '疼痛科',
            'status' => 99, // 无效状态
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.experts.store'), $data);

        $response->assertSessionHasErrors('status');
    }

    /** @test */
    public function admin_can_view_edit_form()
    {
        $expert = Expert::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.experts.edit', $expert));

        $response->assertStatus(200);
        $response->assertViewHas('expert');
    }

    /** @test */
    public function admin_can_update_expert()
    {
        $expert = Expert::factory()->create(['name' => '原名称']);

        $data = [
            'name' => '更新后的名称',
            'title' => '副主任医师',
            'hospital' => '上海瑞金医院',
            'department' => '肿瘤科',
            'status' => 1,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.experts.update', $expert), $data);

        $response->assertRedirect(route('admin.experts.index'));
        $this->assertDatabaseHas('experts', [
            'id' => $expert->id,
            'name' => '更新后的名称',
            'hospital' => '上海瑞金医院',
        ]);
    }

    /** @test */
    public function admin_can_destroy_expert()
    {
        $expert = Expert::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.experts.destroy', $expert));

        $response->assertRedirect(route('admin.experts.index'));
        $this->assertDatabaseMissing('experts', ['id' => $expert->id]);
    }

    /** @test */
    public function admin_can_toggle_expert_status()
    {
        $expert = Expert::factory()->create(['status' => 1]);

        $response = $this->actingAs($this->admin)->put(route('admin.experts.update', $expert), [
            'name' => $expert->name,
            'title' => $expert->title,
            'hospital' => $expert->hospital,
            'department' => $expert->department,
            'status' => 0,
        ]);

        $response->assertRedirect(route('admin.experts.index'));
        $this->assertDatabaseHas('experts', [
            'id' => $expert->id,
            'status' => 0,
        ]);
    }
}
