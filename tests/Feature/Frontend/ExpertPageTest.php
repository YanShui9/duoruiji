<?php

namespace Tests\Feature\Frontend;

use App\Models\Expert;
use App\Models\Lecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function experts_index_page_is_accessible()
    {
        $response = $this->get(route('experts.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function experts_index_displays_paginated_experts()
    {
        Expert::factory()->count(15)->create();

        $response = $this->get(route('experts.index'));

        $response->assertStatus(200);
        $response->assertViewHas('experts');
    }

    /** @test */
    public function experts_index_only_shows_active_experts()
    {
        Expert::factory()->count(3)->create(['status' => 1]);
        Expert::factory()->count(2)->create(['status' => 0]);

        $response = $this->get(route('experts.index'));

        $response->assertStatus(200);
        $response->assertDontSee('禁用'); // 假设禁用状态有不同的显示
    }

    /** @test */
    public function expert_show_page_is_accessible()
    {
        $expert = Expert::factory()->create();

        $response = $this->get(route('experts.show', $expert->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function expert_show_page_returns_404_for_nonexistent_expert()
    {
        $response = $this->get('/experts/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function expert_show_page_displays_lectures()
    {
        $expert = Expert::factory()->create();
        Lecture::factory()->count(3)->create([
            'expert_ids' => [$expert->id],
        ]);

        $response = $this->get(route('experts.show', $expert->id));

        $response->assertStatus(200);
        $response->assertViewHas('lectures');
    }

    /** @test */
    public function expert_show_page_displays_related_experts()
    {
        $expert1 = Expert::factory()->create();
        $expert2 = Expert::factory()->create();
        $lecture = Lecture::factory()->create([
            'expert_ids' => [$expert1->id, $expert2->id],
        ]);

        $response = $this->get(route('experts.show', $expert1->id));

        $response->assertStatus(200);
        $response->assertViewHas('relatedExperts');
    }
}
