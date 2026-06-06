<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'hospital',
        'department',
        'avatar',
        'bio',
        'sort_order',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 获取头像缩略图URL
     */
    public function getThumbAvatarAttribute()
    {
        if (!$this->avatar) return null;
        $thumbPath = str_replace('/experts/', '/experts/thumb_', $this->avatar);
        if (\Storage::disk('public')->exists($thumbPath)) {
            return \Storage::url($thumbPath);
        }
        return \Storage::url($this->avatar);
    }
}
