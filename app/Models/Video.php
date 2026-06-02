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

    public function experts()
    {
        return Expert::whereIn('id', $this->expert_ids ?? [])->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id', 'desc');
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
