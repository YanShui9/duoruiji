<?php

namespace Tests\Feature\Frontend;

use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_is_accessible()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    /** @test */
    public function home_page_displays_stats()
    {
        Expert::factory()->count(5)->create();
        Lecture::factory()->count(3)->create();
        Video::factory()->count(10)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');
    }

    /** @test */
    public function home_page_shows_next_upcoming_lecture()
    {
        $upcoming = Lecture::factory()->upcoming()->create([
            'title' => '即将开始的讲座',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('nextLecture');
    }

    /** @test */
    public function home_page_shows_live_lecture()
    {
        $live = Lecture::factory()->live()->create([
            'title' => '正在直播的讲座',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('liveLecture');
    }

    /** @test */
    public function home_page_shows_recent_ended_lectures()
    {
        Lecture::factory()->count(8)->ended()->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('recentLectures');
    }
}
