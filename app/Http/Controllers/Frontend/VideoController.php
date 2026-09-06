<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $category = request('category');
        $query = Video::active()->with('lecture')->ordered();

        // 搜索视频标题
        if (request()->filled('search')) {
            $search = addcslashes(request('search'), '%_');
            $query->where('title', 'like', "%{$search}%");
        }

        // 筛选专家
        if (request()->filled('expert_id')) {
            $query->whereJsonContains('expert_ids', (int) request('expert_id'));
        }

        // 按讲座分类筛选
        if ($category) {
            $lectureIds = Lecture::where('category', $category)->pluck('id');
            $query->whereIn('lecture_id', $lectureIds);
        }

        // 按指定讲座筛选
        if (request()->filled('lecture_id')) {
            $query->where('lecture_id', (int) request('lecture_id'));
        }

        $videos = $query->paginate(12);
        $categories = Lecture::getCategories();
        $currentCategory = $category;

        // 预加载所有相关专家，避免 N+1 查询
        $allExpertIds = [];
        foreach ($videos as $video) {
            if ($video->expert_ids) {
                $allExpertIds = array_merge($allExpertIds, $video->expert_ids);
            }
        }
        $allExpertIds = array_unique($allExpertIds);
        $expertsMap = Expert::whereIn('id', $allExpertIds)->get()->keyBy('id');

        // 获取专家列表（用于筛选下拉）
        $experts = Expert::active()->ordered()->get();

        // 当按讲座筛选时，获取讲座信息
        $currentLecture = null;
        if (request()->filled('lecture_id')) {
            $currentLecture = Lecture::find(request('lecture_id'));
        }

        return view('frontend.videos.index', compact('videos', 'experts', 'expertsMap', 'categories', 'currentCategory', 'currentLecture'));
    }

    public function show($id)
    {
        $video = Video::with('lecture')->findOrFail($id);
        $experts = $video->experts();

        // 获取同一专家的其他视频
        $relatedVideos = collect();
        if ($experts->count() > 0) {
            $expertIds = $experts->pluck('id')->toArray();
            $relatedVideos = Video::active()
                ->where('id', '!=', $video->id)
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
        $shareUrl = $tunnelUrl ? $tunnelUrl . '/videos/' . $id : url('/videos/' . $id);

        return view('frontend.videos.show', compact('video', 'experts', 'relatedVideos', 'shareUrl'));
    }

    /**
     * 生成二维码图片
     */
    public function qrcode()
    {
        $url = request('url', url('/'));
        $size = request('size', 4);

        // 验证 URL 格式，防止 SSRF 攻击
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//', $url)) {
            $url = url('/');
        }
        // 禁止内网地址
        $host = parse_url($url, PHP_URL_HOST);
        if ($host && (in_array($host, ['localhost', '127.0.0.1', '0.0.0.0', '::1']) || preg_match('/^(10\.|172\.(1[6-9]|2[0-9]|3[01])\.|192\.168\.)/', $host))) {
            $url = url('/');
        }

        // 包含 phpQRcode 库
        include public_path('assets/plugins/phpqrcode/qrlib.php');

        // 设置临时目录
        $tempDir = public_path('assets/plugins/phpqrcode/temp/');

        // 每次请求时清理超过1小时的临时文件（概率清理，避免性能影响）
        if (rand(1, 100) <= 5 && file_exists($tempDir)) {
            $files = glob($tempDir . 'qr_*.png');
            $expireTime = time() - 3600; // 1小时前
            foreach ($files as $file) {
                if (filemtime($file) < $expireTime) {
                    @unlink($file);
                }
            }
        }

        // 确保临时目录存在
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // 生成唯一文件名
        $filename = $tempDir . 'qr_' . md5($url) . '.png';

        // 生成二维码
        \QRcode::png($url, $filename, 'L', $size, 2);

        // 返回图片
        return response()->file($filename, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
