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
        $videos = Video::with('lecture')->ordered()->paginate(15);
        return view('admin.videos.index', compact('videos'));
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
            }
            $data['cover_image'] = $request->file('cover_image')->store('videos', 'public');
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
        }
        if ($video->video_url) {
            Storage::disk('public')->delete($video->video_url);
        }
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', '视频删除成功');
    }
}
