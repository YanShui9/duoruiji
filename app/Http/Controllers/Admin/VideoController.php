<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Models\Video;
use App\Models\Lecture;
use App\Models\Expert;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $query = Video::with('lecture')->ordered();

        // 关键词搜索
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where('title', 'like', "%{$search}%");
        }

        // 按讲座筛选
        if (request()->filled('lecture_id')) {
            $query->where('lecture_id', request('lecture_id'));
        }

        // 按状态筛选
        if (request()->filled('status') || request('status') === '0') {
            $query->where('status', request('status'));
        }

        $videos = $query->paginate(15)->appends(request()->query());

        // 获取讲座列表用于筛选
        $lectures = \App\Models\Lecture::orderBy('title')->pluck('title', 'id');

        return view('admin.videos.index', compact('videos', 'lectures'));
    }

    public function create()
    {
        $lectures = Lecture::ordered()->get();
        $experts = Expert::active()->ordered()->get();
        return view('admin.videos.create', compact('lectures', 'experts'));
    }

    public function store(VideoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('videos', 'public');
            $this->createThumbnail($data['cover_image']);
        }

        if ($request->hasFile('video_file')) {
            $data['video_url'] = $request->file('video_file')->store('videos/files', 'public');
        }

        Video::create($data);

        return redirect()->route('admin.videos.index')->with('success', '视频添加成功');
    }

    public function edit(Video $video)
    {
        $lectures = Lecture::ordered()->get();
        $experts = Expert::active()->ordered()->get();
        return view('admin.videos.edit', compact('video', 'lectures', 'experts'));
    }

    public function update(VideoRequest $request, Video $video)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($video->cover_image) {
                Storage::disk('public')->delete($video->cover_image);
                // 删除旧缩略图
                $dir = pathinfo($video->cover_image, PATHINFO_DIRNAME);
                $filename = pathinfo($video->cover_image, PATHINFO_BASENAME);
                $oldThumb = $dir . '/thumb_' . $filename;
                Storage::disk('public')->delete($oldThumb);
            }
            $data['cover_image'] = $request->file('cover_image')->store('videos', 'public');
            $this->createThumbnail($data['cover_image']);
        }

        if ($request->hasFile('video_file')) {
            if ($video->video_url) {
                Storage::disk('public')->delete($video->video_url);
            }
            $data['video_url'] = $request->file('video_file')->store('videos/files', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', '视频更新成功');
    }

    public function destroy(Video $video)
    {
        if ($video->cover_image) {
            Storage::disk('public')->delete($video->cover_image);
            // 删除缩略图
            $dir = pathinfo($video->cover_image, PATHINFO_DIRNAME);
            $filename = pathinfo($video->cover_image, PATHINFO_BASENAME);
            $thumb = $dir . '/thumb_' . $filename;
            Storage::disk('public')->delete($thumb);
        }
        if ($video->video_url) {
            Storage::disk('public')->delete($video->video_url);
        }
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', '视频删除成功');
    }

    private function createThumbnail($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            return;
        }
        $thumbPath = str_replace('/videos/', '/videos/thumb_', $path);

        \Image::make($fullPath)
            ->fit(400, 225)
            ->save(storage_path('app/public/' . $thumbPath));
    }
}
