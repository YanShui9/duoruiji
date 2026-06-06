<?php

namespace Tests\Feature\Frontend;

use App\Models\Lecture;
use App\Models\Expert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecturePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lectures_index_page_is_accessible()
    {
        $response = $this->get(route('lectures.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function lectures_index_displays_paginated_lectures()
    {
        Lecture::factory()->count(15)->upcoming()->create();

        $response = $this->get(route('lectures.index'));

        $response->assertStatus(200);
        $response->assertViewHas('lectures');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function lectures_index_can_filter_by_category()
    {
        Lecture::factory()->count(3)->create(['category' => 'cancer_pain']);
        Lecture::factory()->count(2)->create(['category' => 'pain_management']);

        $response = $this->get(route('lectures.index', ['category' => 'cancer_pain']));

        $response->assertStatus(200);
        $response->assertViewHas('currentCategory', 'cancer_pain');
    }

    /** @test */
    public function lecture_show_page_is_accessible()
    {
        $lecture = Lecture::factory()->upcoming()->create();

        $response = $this->get(route('lectures.show', $lecture->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function lecture_show_page_returns_404_for_nonexistent_lecture()
    {
        $response = $this->get('/lectures/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function lecture_show_page_displays_experts()
    {
        $expert = Expert::factory()->create();
        $lecture = Lecture::factory()->upcoming()->create([
            'expert_ids' => [$expert->id],
        ]);

        $response = $this->get(route('lectures.show', $lecture->id));

        $response->assertStatus(200);
        $response->assertViewHas('experts');
    }

    /** @test */
    public function lecture_show_page_displays_related_lectures()
    {
        $expert = Expert::factory()->create();
        $lecture = Lecture::factory()->upcoming()->create([
            'expert_ids' => [$expert->id],
        ]);
        Lecture::factory()->count(3)->upcoming()->create([
            'expert_ids' => [$expert->id],
        ]);

        $response = $this->get(route('lectures.show', $lecture->id));

        $response->assertStatus(200);
        $response->assertViewHas('relatedLectures');
    }
}
