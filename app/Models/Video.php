<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecture_id',
        'title',
        'description',
        'video_url',
        'cover_image',
        'duration',
        'expert_ids',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'expert_ids' => 'array',
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    private $cachedExperts = null;

    public function experts()
    {
        if ($this->cachedExperts !== null) {
            return $this->cachedExperts;
        }
        $this->cachedExperts = Expert::whereIn('id', $this->expert_ids ?? [])->get();
        return $this->cachedExperts;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * 获取封面缩略图URL
     */
    public function getThumbCoverAttribute()
    {
        if (!$this->cover_image) return null;
        $thumbPath = str_replace('/videos/', '/videos/thumb_', $this->cover_image);
        if (\Storage::disk('public')->exists($thumbPath)) {
            return \Storage::url($thumbPath);
        }
        return \Storage::url($this->cover_image);
    }
}
