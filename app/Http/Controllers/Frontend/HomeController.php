<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Expert;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        // 获取下一场直播
        $nextLecture = Lecture::where('status', 0)
            ->where('live_start_time', '>', now())
            ->orderBy('live_start_time')
            ->first();

        // 获取当前正在直播的讲座
        $liveLecture = Lecture::where('status', 1)->first();

        // 获取本期专家（最新讲座的专家）
        $latestLecture = Lecture::ordered()->first();
        $currentExperts = $latestLecture ? $latestLecture->experts() : collect();

        // 获取精彩回顾（最近结束的讲座）
        $recentLectures = Lecture::where('status', 2)
            ->orderBy('live_start_time', 'desc')
            ->limit(6)
            ->get();

        return view('frontend.home', compact(
            'nextLecture',
            'liveLecture',
            'currentExperts',
            'recentLectures'
        ));
    }
}
