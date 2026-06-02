<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::active()
            ->with('lecture')
            ->ordered()
            ->paginate(12);

        return view('frontend.videos.index', compact('videos'));
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
