<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'cover_image',
        'live_url',
        'live_start_time',
        'live_end_time',
        'status',
        'expert_ids',
        'sort_order',
    ];

    protected $casts = [
        'live_start_time' => 'datetime',
        'live_end_time' => 'datetime',
        'expert_ids' => 'array',
    ];

    private $cachedExperts = null;

    public function experts()
    {
        if ($this->cachedExperts !== null) {
            return $this->cachedExperts;
        }
        $this->cachedExperts = Expert::whereIn('id', $this->expert_ids ?? [])->get();
        return $this->cachedExperts;
    }

    public function getIsLiveAttribute()
    {
        return $this->status === 1;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->status === 0 && $this->live_start_time && $this->live_start_time->isFuture();
    }

    public function getIsEndedAttribute()
    {
        return $this->status === 2;
    }

    public function getNextLecture()
    {
        return static::where('status', 0)
            ->where('live_start_time', '>', now())
            ->orderBy('live_start_time')
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 0)->orWhere(function ($q) {
            $q->where('status', 0)->where('live_start_time', '>', now());
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw("CASE WHEN status = 1 THEN 0 WHEN status = 0 THEN 1 ELSE 2 END")
            ->orderBy('id', 'desc');
    }

    /**
     * 按分类筛选
     */
    public function scopeOfCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * 获取所有分类
     */
    public static function getCategories()
    {
        return [
            'cancer_pain' => '癌痛治疗',
            'pain_management' => '疼痛管理',
            'clinical_research' => '临床研究',
            'academic_conference' => '学术会议',
        ];
    }

    /**
     * 获取分类中文名称
     */
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? '';
    }

    /**
     * 获取封面缩略图URL
     */
    public function getThumbCoverAttribute()
    {
        if (!$this->cover_image) return null;
        $thumbPath = str_replace('/lectures/', '/lectures/thumb_', $this->cover_image);
        if (\Storage::disk('public')->exists($thumbPath)) {
            return \Storage::url($thumbPath);
        }
        return \Storage::url($this->cover_image);
    }

    /**
     * 自动更新直播状态（在页面加载时调用）
     */
    public static function updateExpiredStatuses()
    {
        $now = Carbon::now();

        // 未开始 → 直播中
        static::where('status', 0)
            ->where('live_start_time', '<=', $now)
            ->where('live_end_time', '>', $now)
            ->update(['status' => 1]);

        // 未开始 → 已结束（已过期未开播）
        static::where('status', 0)
            ->where('live_end_time', '<=', $now)
            ->update(['status' => 2]);

        // 直播中 → 已结束
        static::where('status', 1)
            ->where('live_end_time', '<=', $now)
            ->update(['status' => 2]);
    }
}
