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
        // 自动更新过期的直播状态
        Lecture::updateExpiredStatuses();

        // 获取下一场直播
        $nextLecture = Lecture::where('status', 0)
            ->where('live_start_time', '>', now())
            ->orderBy('live_start_time')
            ->first();

        // 获取正在直播的讲座（限制10条，避免全量查询）
        $liveLectures = Lecture::where('status', 1)
            ->orderBy('live_start_time', 'desc')
            ->limit(10)
            ->get();

        // 当前显示的直播（取第一个）
        $liveLecture = $liveLectures->first();

        // 获取本期专家（当前直播的专家，多个直播时取第一个直播的专家）
        $currentExperts = collect();
        if ($liveLecture) {
            $currentExperts = $liveLecture->experts();
        } elseif ($nextLecture) {
            $currentExperts = $nextLecture->experts();
        }

        // 获取精彩回顾（最近结束的讲座）
        $recentLectures = Lecture::where('status', 2)
            ->orderBy('live_start_time', 'desc')
            ->limit(6)
            ->get();

        // 统计数据（避免在视图中直接查询）
        $stats = [
            'experts' => \App\Models\Expert::count(),
            'lectures' => \App\Models\Lecture::count(),
            'videos' => \App\Models\Video::count(),
            'live_now' => $liveLectures->count(),
        ];

        return view('frontend.home', compact(
            'nextLecture',
            'liveLecture',
            'liveLectures',
            'currentExperts',
            'recentLectures',
            'stats'
        ));
    }
}
