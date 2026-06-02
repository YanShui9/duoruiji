<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lecture;

class LectureController extends Controller
{
    public function index()
    {
        $lectures = Lecture::ordered()->paginate(12);
        return view('frontend.lectures.index', compact('lectures'));
    }

    public function show($id)
    {
        $lecture = Lecture::findOrFail($id);
        $experts = $lecture->experts();

        // 获取同一专家的其他讲座
        $relatedLectures = collect();
        if ($experts->count() > 0) {
            $expertIds = $experts->pluck('id')->toArray();
            $relatedLectures = Lecture::where('id', '!=', $lecture->id)
                ->where(function ($query) use ($expertIds) {
                    foreach ($expertIds as $expertId) {
                        $query->orWhereJsonContains('expert_ids', $expertId);
                    }
                })
                ->limit(4)
                ->get();
        }

        return view('frontend.lectures.show', compact('lecture', 'experts', 'relatedLectures'));
    }
}
