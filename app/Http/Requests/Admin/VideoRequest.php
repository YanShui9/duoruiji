<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'lecture_id' => 'required|exists:lectures,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'duration' => 'nullable|integer|min:0',
            'expert_ids' => 'nullable|array',
            'expert_ids.*' => 'exists:experts,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ];

        if ($this->isMethod('POST')) {
            $rules['video_file'] = 'required|mimes:mp4,avi,wmv|max:512000'; // 500MB
        } else {
            $rules['video_file'] = 'nullable|mimes:mp4,avi,wmv|max:512000';
        }

        return $rules;
    }
}
