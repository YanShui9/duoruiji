<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $query = Video::active()->with('lecture')->ordered();

        // 搜索视频标题
        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // 筛选专家
        if ($expertId = request('expert_id')) {
            $query->whereJsonContains('expert_ids', (int) $expertId);
        }

        // 日期范围筛选
        if ($dateFrom = request('date_from')) {
            $query->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo = request('date_to')) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $videos = $query->paginate(12);

        // 获取专家列表
        $experts = Expert::active()->ordered()->get();

        return view('frontend.videos.index', compact('videos', 'experts'));
    }

    public function show($id)
    {
        $video = Video::with('lecture')->findOrFail($id);
        $experts = $video->experts();

        // 获取同一专家的其他视频
        $relatedVideos = collect();
        if ($experts->count() > 0) {
            $expertIds = $experts->pluck('id')->toArray();
            $relatedVideos = Video::active()
                ->where('id', '!=', $video->id)
                ->where(function ($query) use ($expertIds) {
                    foreach ($expertIds as $expertId) {
                        $query->orWhereJsonContains('expert_ids', $expertId);
                    }
                })
                ->limit(4)
                ->get();
        }

        return view('frontend.videos.show', compact('video', 'experts', 'relatedVideos'));
    }
}
