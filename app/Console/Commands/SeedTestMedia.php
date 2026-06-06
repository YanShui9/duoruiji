<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;

class SeedTestMedia extends Command
{
    protected $signature = 'seed:test-media';
    protected $description = '为测试数据下载封面图片和视频文件';

    public function handle()
    {
        $this->info('开始下载测试媒体文件...');

        $this->seedVideos();
        $this->seedImages(User::class, 'users', 'avatar', 200, 200);
        $this->seedImages(Expert::class, 'experts', 'avatar', 200, 200);
        $this->seedImages(Lecture::class, 'lectures', 'cover_image', 400, 225);
        $this->seedImages(Video::class, 'videos', 'cover_image', 400, 225);

        $this->info('完成！');
        return 0;
    }

    private function seedVideos()
    {
        $source = storage_path('app/public/videos/files/test_video_17.mp4');
        if (!file_exists($source)) {
            $this->error('源视频文件不存在: test_video_17.mp4');
            return;
        }

        $count = 0;
        foreach (Video::all() as $video) {
            $target = storage_path('app/public/' . $video->video_url);
            if (file_exists($target)) continue;

            $dir = dirname($target);
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            copy($source, $target);
            $count++;
            $this->line("  [视频] {$video->video_url}");
        }
        $this->info("视频文件: 复制 {$count} 个");
    }

    private function seedImages($model, $dir, $field, $w, $h)
    {
        $items = $model::whereNull($field)->get();
        $count = 0;

        foreach ($items as $item) {
            $seed = strtolower(class_basename($model)) . $item->id;
            $url = "https://picsum.photos/seed/{$seed}/{$w}/{$h}";
            $path = "{$dir}/" . uniqid() . '.jpg';
            $fullPath = storage_path('app/public/' . $path);

            if (!is_dir(dirname($fullPath))) mkdir(dirname($fullPath), 0755, true);

            $data = $this->download($url);
            if (!$data) {
                $this->warn("  [跳过] #{$item->id} 下载失败");
                continue;
            }

            file_put_contents($fullPath, $data);
            $item->update([$field => $path]);

            $thumbPath = str_replace("/{$dir}/", "/{$dir}/thumb_", $path);
            try {
                \Image::make($fullPath)->fit($w, $h)->save(storage_path('app/public/' . $thumbPath));
            } catch (\Exception $e) {}

            $count++;
            $this->line("  [{$dir}] #{$item->id}");
        }

        $this->info("{$dir}: 下载 {$count} 个");
    }

    private function download($url)
    {
        $ctx = stream_context_create([
            'http' => ['timeout' => 10, 'user_agent' => 'Mozilla/5.0'],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);
        return @file_get_contents($url, false, $ctx);
    }
}
