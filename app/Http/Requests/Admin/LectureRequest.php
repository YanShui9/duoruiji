<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LectureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'live_url' => 'nullable|url|max:500',
            'live_start_time' => 'nullable|date',
            'live_end_time' => 'nullable|date|after_or_equal:live_start_time',
            'status' => 'required|in:0,1,2',
            'expert_ids' => 'nullable|array',
            'expert_ids.*' => 'exists:experts,id',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
