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
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where('title', 'like', "%{$search}%");
        }

        // 筛选状态
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // 筛选分类
        if ($category) {
            $query->ofCategory($category);
        }

        $lectures = $query->paginate(12);
        $categories = Lecture::getCategories();
        $currentCategory = $category;

        // 预加载所有相关专家，避免 N+1 查询
        $allExpertIds = [];
        foreach ($lectures as $lecture) {
            if ($lecture->expert_ids) {
                $allExpertIds = array_merge($allExpertIds, $lecture->expert_ids);
            }
        }
        $allExpertIds = array_unique($allExpertIds);
        $expertsMap = \App\Models\Expert::whereIn('id', $allExpertIds)->get()->keyBy('id');

        return view('frontend.lectures.index', compact('lectures', 'categories', 'currentCategory', 'expertsMap'));
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

        // 生成分享链接（使用 tunnel_url 或当前 URL）
        $tunnelUrl = config('app.tunnel_url');
        $shareUrl = $tunnelUrl ? $tunnelUrl . '/lectures/' . $id : url('/lectures/' . $id);

        return view('frontend.lectures.show', compact('lecture', 'experts', 'relatedLectures', 'shareUrl'));
    }
}
