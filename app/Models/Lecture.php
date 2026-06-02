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

    public function experts()
    {
        return Expert::whereIn('id', $this->expert_ids ?? [])->get();
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
        return $query->orderBy('sort_order')->orderBy('live_start_time', 'desc');
    }
}
