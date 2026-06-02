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
        $experts = Expert::ordered()->paginate(15);
        return view('admin.experts.index', compact('experts'));
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
                $oldThumb = str_replace('/experts/', '/experts/thumb_', $expert->avatar);
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
            $thumb = str_replace('/experts/', '/experts/thumb_', $expert->avatar);
            Storage::disk('public')->delete($thumb);
        }
        $expert->delete();

        return redirect()->route('admin.experts.index')->with('success', '专家删除成功');
    }

    private function createThumbnail($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        $thumbPath = str_replace('/experts/', '/experts/thumb_', $path);

        \Image::make($fullPath)
            ->fit(200, 200)
            ->save(storage_path('app/public/' . $thumbPath));
    }
}
