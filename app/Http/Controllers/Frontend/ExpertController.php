<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Lecture;

class ExpertController extends Controller
{
    public function index()
    {
        $query = Expert::active()->ordered();

        // 搜索专家姓名
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where('name', 'like', "%{$search}%");
        }

        // 筛选医院
        if (request()->filled('hospital')) {
            $query->where('hospital', request('hospital'));
        }

        // 筛选科室
        if (request()->filled('department')) {
            $query->where('department', request('department'));
        }

        $experts = $query->paginate(12);

        // 获取筛选选项
        $hospitals = Expert::active()->distinct()->pluck('hospital');
        $departments = Expert::active()->distinct()->pluck('department');

        return view('frontend.experts.index', compact('experts', 'hospitals', 'departments'));
    }

    public function show($id)
    {
        $expert = Expert::findOrFail($id);

        // 获取该专家参与的讲座
        $lectures = Lecture::whereJsonContains('expert_ids', $expert->id)
            ->ordered()
            ->limit(10)
            ->get();

        // 获取相关专家（参加同一期讲座的其他专家）
        $relatedExperts = collect();
        $lectureIds = $lectures->pluck('id')->toArray();

        if (count($lectureIds) > 0) {
            $relatedExpertIds = [];
            foreach ($lectures as $lecture) {
                if ($lecture->expert_ids) {
                    $relatedExpertIds = array_merge($relatedExpertIds, $lecture->expert_ids);
                }
            }
            $relatedExpertIds = array_unique($relatedExpertIds);
            $relatedExpertIds = array_diff($relatedExpertIds, [$expert->id]);

            if (count($relatedExpertIds) > 0) {
                $relatedExperts = Expert::active()
                    ->whereIn('id', $relatedExpertIds)
                    ->limit(6)
                    ->get();
            }
        }

        return view('frontend.experts.show', compact('expert', 'lectures', 'relatedExperts'));
    }
}
