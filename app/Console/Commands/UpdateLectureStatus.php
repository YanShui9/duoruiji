<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lecture;
use Carbon\Carbon;

class UpdateLectureStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lectures:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动更新直播状态：未开始→直播中→已结束';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $updated = 0;

        // 1. 未开始 → 直播中：当前时间在开始时间和结束时间之间
        $toLive = Lecture::where('status', 0)
            ->where('live_start_time', '<=', $now)
            ->where('live_end_time', '>', $now)
            ->update(['status' => 1]);

        // 2. 未开始 → 已结束：当前时间已过结束时间（跳过直播直接结束）
        $toEnded = Lecture::where('status', 0)
            ->where('live_end_time', '<=', $now)
            ->update(['status' => 2]);

        // 3. 直播中 → 已结束：当前时间已过结束时间
        $liveToEnded = Lecture::where('status', 1)
            ->where('live_end_time', '<=', $now)
            ->update(['status' => 2]);

        $updated = $toLive + $toEnded + $liveToEnded;

        if ($updated > 0) {
            $this->info("已更新 {$updated} 个直播状态");
            $this->line("  未开始→直播中: {$toLive}");
            $this->line("  未开始→已结束: {$toEnded}");
            $this->line("  直播中→已结束: {$liveToEnded}");
        } else {
            $this->line('没有需要更新的直播状态');
        }

        return Command::SUCCESS;
    }
}
