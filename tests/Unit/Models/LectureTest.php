<?php

namespace Tests\Unit\Models;

use App\Models\Lecture;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LectureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_lecture()
    {
        $lecture = Lecture::factory()->create([
            'title' => '癌痛治疗新进展',
            'category' => 'cancer_pain',
            'status' => 0,
        ]);

        $this->assertDatabaseHas('lectures', [
            'title' => '癌痛治疗新进展',
            'category' => 'cancer_pain',
            'status' => 0,
        ]);
    }

    /** @test */
    public function it_casts_live_times_to_datetime()
    {
        $lecture = Lecture::factory()->create([
            'live_start_time' => '2026-06-10 14:00:00',
            'live_end_time' => '2026-06-10 16:00:00',
        ]);

        $this->assertInstanceOf(Carbon::class, $lecture->live_start_time);
        $this->assertInstanceOf(Carbon::class, $lecture->live_end_time);
    }

    /** @test */
    public function it_casts_expert_ids_to_array()
    {
        $lecture = Lecture::factory()->create([
            'expert_ids' => [1, 2, 3],
        ]);

        // 重新从数据库获取以测试 cast
        $lecture = Lecture::find($lecture->id);
        $this->assertIsArray($lecture->expert_ids);
        $this->assertEquals([1, 2, 3], $lecture->expert_ids);
    }

    /** @test */
    public function it_correctly_identifies_live_status()
    {
        $lecture = Lecture::factory()->live()->create();
        $this->assertTrue($lecture->is_live);

        $lecture2 = Lecture::factory()->upcoming()->create();
        $this->assertFalse($lecture2->is_live);
    }

    /** @test */
    public function it_correctly_identifies_upcoming_status()
    {
        $lecture = Lecture::factory()->upcoming()->create();
        $this->assertTrue($lecture->is_upcoming);

        $lecture2 = Lecture::factory()->live()->create();
        $this->assertFalse($lecture2->is_upcoming);
    }

    /** @test */
    public function it_correctly_identifies_ended_status()
    {
        $lecture = Lecture::factory()->ended()->create();
        $this->assertTrue($lecture->is_ended);

        $lecture2 = Lecture::factory()->live()->create();
        $this->assertFalse($lecture2->is_ended);
    }

    /** @test */
    public function it_can_get_categories()
    {
        $categories = Lecture::getCategories();

        $this->assertArrayHasKey('cancer_pain', $categories);
        $this->assertArrayHasKey('pain_management', $categories);
        $this->assertArrayHasKey('clinical_research', $categories);
        $this->assertArrayHasKey('academic_conference', $categories);
        $this->assertEquals('癌痛治疗', $categories['cancer_pain']);
    }

    /** @test */
    public function it_can_get_category_name_attribute()
    {
        $lecture = Lecture::factory()->create(['category' => 'cancer_pain']);
        $this->assertEquals('癌痛治疗', $lecture->category_name);

        $lecture2 = Lecture::factory()->create(['category' => 'pain_management']);
        $this->assertEquals('疼痛管理', $lecture2->category_name);
    }

    /** @test */
    public function it_returns_empty_string_for_unknown_category()
    {
        $lecture = Lecture::factory()->create(['category' => 'unknown']);
        $this->assertEquals('', $lecture->category_name);
    }

    /** @test */
    public function it_can_scope_of_category()
    {
        Lecture::factory()->count(3)->create(['category' => 'cancer_pain']);
        Lecture::factory()->count(2)->create(['category' => 'pain_management']);

        $result = Lecture::ofCategory('cancer_pain')->get();
        $this->assertCount(3, $result);

        $result2 = Lecture::ofCategory(null)->get();
        $this->assertCount(5, $result2);
    }

    /** @test */
    public function it_can_update_expired_statuses()
    {
        // 创建一个已经过期的未开始讲座
        $expiredUpcoming = Lecture::factory()->create([
            'status' => 0,
            'live_start_time' => now()->subDays(2),
            'live_end_time' => now()->subDay(),
        ]);

        // 创建一个正在进行的讲座（已过结束时间）
        $expiredLive = Lecture::factory()->create([
            'status' => 1,
            'live_start_time' => now()->subDays(2),
            'live_end_time' => now()->subDay(),
        ]);

        // 创建一个应该变为直播中的讲座
        $shouldBeLive = Lecture::factory()->create([
            'status' => 0,
            'live_start_time' => now()->subHour(),
            'live_end_time' => now()->addHour(),
        ]);

        Lecture::updateExpiredStatuses();

        $this->assertEquals(2, $expiredUpcoming->fresh()->status);
        $this->assertEquals(2, $expiredLive->fresh()->status);
        $this->assertEquals(1, $shouldBeLive->fresh()->status);
    }

    /** @test */
    public function it_can_get_next_lecture()
    {
        // 创建已结束的讲座
        Lecture::factory()->ended()->create();

        // 创建即将开始的讲座
        $upcoming1 = Lecture::factory()->create([
            'status' => 0,
            'live_start_time' => now()->addDays(3),
        ]);
        $upcoming2 = Lecture::factory()->create([
            'status' => 0,
            'live_start_time' => now()->addDay(),
        ]);

        $lecture = new Lecture();
        $next = $lecture->getNextLecture();

        $this->assertNotNull($next);
        $this->assertEquals($upcoming2->id, $next->id);
    }
}
