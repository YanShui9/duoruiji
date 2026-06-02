<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LectureRequest;
use App\Models\Lecture;
use App\Models\Expert;
use Illuminate\Support\Facades\Storage;

class LectureController extends Controller
{
    public function index()
    {
        $lectures = Lecture::ordered()->paginate(15);
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
            }
            $data['cover_image'] = $request->file('cover_image')->store('lectures', 'public');
        }

        $lecture->update($data);

        return redirect()->route('admin.lectures.index')->with('success', '讲座更新成功');
    }

    public function destroy(Lecture $lecture)
    {
        if ($lecture->cover_image) {
            Storage::disk('public')->delete($lecture->cover_image);
        }
        $lecture->delete();

        return redirect()->route('admin.lectures.index')->with('success', '讲座删除成功');
    }
}
