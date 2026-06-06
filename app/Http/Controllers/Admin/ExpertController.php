<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpertRequest;
use App\Models\Expert;
use Illuminate\Support\Facades\Storage;

class ExpertController extends Controller
{
    public function index()
    {
        $query = Expert::ordered();

        // 关键词搜索
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('hospital', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // 按职称筛选
        if (request()->filled('title')) {
            $titleSearch = addcslashes(request('title'), '%_');
            $query->where('title', 'like', "%{$titleSearch}%");
        }

        // 按医院筛选
        if (request()->filled('hospital')) {
            $query->where('hospital', request('hospital'));
        }

        // 按科室筛选
        if (request()->filled('department')) {
            $query->where('department', request('department'));
        }

        // 按状态筛选
        if (request()->filled('status') || request('status') === '0') {
            $query->where('status', request('status'));
        }

        $experts = $query->paginate(15)->appends(request()->query());

        // 获取筛选选项
        $hospitals = Expert::whereNotNull('hospital')->distinct()->pluck('hospital')->sort();
        $departments = Expert::whereNotNull('department')->distinct()->pluck('department')->sort();
        $titles = Expert::whereNotNull('title')->distinct()->pluck('title')->sort();

        return view('admin.experts.index', compact('experts', 'hospitals', 'departments', 'titles'));
    }

    public function create()
    {
        return view('admin.experts.create');
    }

    public function store(ExpertRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('experts', 'public');
            $this->createThumbnail($data['avatar']);
        }

        Expert::create($data);

        return redirect()->route('admin.experts.index')->with('success', '专家添加成功');
    }

    public function edit(Expert $expert)
    {
        return view('admin.experts.edit', compact('expert'));
    }

    public function update(ExpertRequest $request, Expert $expert)
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            // 删除旧头像
            if ($expert->avatar) {
                Storage::disk('public')->delete($expert->avatar);
                // 删除旧缩略图
                $dir = pathinfo($expert->avatar, PATHINFO_DIRNAME);
                $filename = pathinfo($expert->avatar, PATHINFO_BASENAME);
                $oldThumb = $dir . '/thumb_' . $filename;
                Storage::disk('public')->delete($oldThumb);
            }
            $data['avatar'] = $request->file('avatar')->store('experts', 'public');
            $this->createThumbnail($data['avatar']);
        }

        $expert->update($data);

        return redirect()->route('admin.experts.index')->with('success', '专家更新成功');
    }

    public function destroy(Expert $expert)
    {
        if ($expert->avatar) {
            Storage::disk('public')->delete($expert->avatar);
            $dir = pathinfo($expert->avatar, PATHINFO_DIRNAME);
            $filename = pathinfo($expert->avatar, PATHINFO_BASENAME);
            $thumb = $dir . '/thumb_' . $filename;
            Storage::disk('public')->delete($thumb);
        }
        $expert->delete();

        return redirect()->route('admin.experts.index')->with('success', '专家删除成功');
    }

    private function createThumbnail($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            return;
        }
        $thumbPath = str_replace('/experts/', '/experts/thumb_', $path);

        \Image::make($fullPath)
            ->fit(200, 200)
            ->save(storage_path('app/public/' . $thumbPath));
    }
}
