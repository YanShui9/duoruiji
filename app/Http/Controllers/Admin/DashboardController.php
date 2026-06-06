<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // 自动更新过期的直播状态
        Lecture::updateExpiredStatuses();

        $stats = [
            'experts' => Expert::count(),
            'lectures' => Lecture::count(),
            'videos' => Video::count(),
            'live_now' => Lecture::where('status', 1)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * 上传图片（用于富文本编辑器）
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('content', 'public');

        return Storage::url($path);
    }
}
