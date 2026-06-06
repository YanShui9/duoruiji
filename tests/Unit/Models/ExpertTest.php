<?php

namespace Tests\Unit\Models;

use App\Models\Expert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_expert()
    {
        $expert = Expert::factory()->create([
            'name' => '张医生',
            'title' => '主任医师',
            'hospital' => '北京协和医院',
            'department' => '疼痛科',
        ]);

        $this->assertDatabaseHas('experts', [
            'name' => '张医生',
            'title' => '主任医师',
            'hospital' => '北京协和医院',
            'department' => '疼痛科',
        ]);
    }

    /** @test */
    public function it_can_scope_active_experts()
    {
        Expert::factory()->count(3)->create(['status' => 1]);
        Expert::factory()->count(2)->create(['status' => 0]);

        $activeExperts = Expert::active()->get();

        $this->assertCount(3, $activeExperts);
        $activeExperts->each(function ($expert) {
            $this->assertEquals(1, $expert->status);
        });
    }

    /** @test */
    public function it_can_scope_ordered_experts()
    {
        Expert::factory()->create(['sort_order' => 3, 'id' => 1]);
        Expert::factory()->create(['sort_order' => 1, 'id' => 2]);
        Expert::factory()->create(['sort_order' => 2, 'id' => 3]);

        $orderedExperts = Expert::ordered()->get();

        // scopeOrdered 按 id desc 排序
        $this->assertEquals(3, $orderedExperts[0]->id);
        $this->assertEquals(2, $orderedExperts[1]->id);
        $this->assertEquals(1, $orderedExperts[2]->id);
    }

    /** @test */
    public function it_returns_null_for_thumb_avatar_when_no_avatar()
    {
        $expert = Expert::factory()->create(['avatar' => null]);

        $this->assertNull($expert->thumb_avatar);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $expert = new Expert();

        $expected = ['name', 'title', 'hospital', 'department', 'avatar', 'bio', 'sort_order', 'status'];
        $this->assertEquals($expected, $expert->getFillable());
    }
}
