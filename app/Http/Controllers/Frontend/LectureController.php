<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lecture;

class LectureController extends Controller
{
    public function index()
    {
        // 自动更新过期的直播状态
        Lecture::updateExpiredStatuses();

        $category = request('category');
        $query = Lecture::ordered();

        // 搜索讲座标题
        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // 筛选状态
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // 筛选分类
        if ($category) {
            $query->ofCategory($category);
        }

        $lectures = $query->paginate(12);
        $categories = Lecture::getCategories();
        $currentCategory = $category;

        return view('frontend.lectures.index', compact('lectures', 'categories', 'currentCategory'));
    }

    public function show($id)
    {
        // 自动更新过期的直播状态
        Lecture::updateExpiredStatuses();

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
