<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LectureRequest;
use App\Models\Lecture;
use App\Models\Video;
use App\Models\Expert;
use Illuminate\Support\Facades\Storage;

class LectureController extends Controller
{
    public function index()
    {
        // 自动更新过期的直播状态
        Lecture::updateExpiredStatuses();

        $query = Lecture::ordered();

        // 关键词搜索
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where('title', 'like', "%{$search}%");
        }

        // 按分类筛选
        if (request()->filled('category')) {
            $query->where('category', request('category'));
        }

        // 按状态筛选
        if (request()->filled('status') || request('status') === '0') {
            $query->where('status', request('status'));
        }

        $lectures = $query->paginate(15)->appends(request()->query());
        return view('admin.lectures.index', compact('lectures'));
    }

    public function create()
    {
        $experts = Expert::active()->ordered()->get();
        return view('admin.lectures.create', compact('experts'));
    }

    public function store(LectureRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('lectures', 'public');
            $this->createThumbnail($data['cover_image']);
        }

        Lecture::create($data);

        return redirect()->route('admin.lectures.index')->with('success', '讲座添加成功');
    }

    public function edit(Lecture $lecture)
    {
        $experts = Expert::active()->ordered()->get();
        return view('admin.lectures.edit', compact('lecture', 'experts'));
    }

    public function update(LectureRequest $request, Lecture $lecture)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($lecture->cover_image) {
                Storage::disk('public')->delete($lecture->cover_image);
                // 删除旧缩略图
                $dir = pathinfo($lecture->cover_image, PATHINFO_DIRNAME);
                $filename = pathinfo($lecture->cover_image, PATHINFO_BASENAME);
                $oldThumb = $dir . '/thumb_' . $filename;
                Storage::disk('public')->delete($oldThumb);
            }
            $data['cover_image'] = $request->file('cover_image')->store('lectures', 'public');
            $this->createThumbnail($data['cover_image']);
        }

        $lecture->update($data);

        return redirect()->route('admin.lectures.index')->with('success', '讲座更新成功');
    }

    public function destroy(Lecture $lecture)
    {
        // 删除关联视频
        $videos = Video::where('lecture_id', $lecture->id)->get();
        foreach ($videos as $video) {
            if ($video->cover_image) {
                Storage::disk('public')->delete($video->cover_image);
                // 删除视频缩略图
                $vDir = pathinfo($video->cover_image, PATHINFO_DIRNAME);
                $vFilename = pathinfo($video->cover_image, PATHINFO_BASENAME);
                $thumb = $vDir . '/thumb_' . $vFilename;
                Storage::disk('public')->delete($thumb);
            }
            if ($video->video_url) {
                Storage::disk('public')->delete($video->video_url);
            }
            $video->delete();
        }

        if ($lecture->cover_image) {
            Storage::disk('public')->delete($lecture->cover_image);
            // 删除讲座缩略图
            $dir = pathinfo($lecture->cover_image, PATHINFO_DIRNAME);
            $filename = pathinfo($lecture->cover_image, PATHINFO_BASENAME);
            $thumb = $dir . '/thumb_' . $filename;
            Storage::disk('public')->delete($thumb);
        }
        $lecture->delete();

        return redirect()->route('admin.lectures.index')->with('success', '讲座及其关联视频已删除');
    }

    private function createThumbnail($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            return;
        }
        $thumbPath = str_replace('/lectures/', '/lectures/thumb_', $path);

        \Image::make($fullPath)
            ->fit(400, 225)
            ->save(storage_path('app/public/' . $thumbPath));
    }
}
