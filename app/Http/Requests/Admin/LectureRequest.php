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
            'category' => 'nullable|string|in:cancer_pain,pain_management,clinical_research,academic_conference',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'live_url' => 'nullable|url|max:500|regex:/^https?:\/\//',
            'live_start_time' => 'nullable|date',
            'live_end_time' => 'nullable|date|after:live_start_time',
            'status' => 'required|in:0,1,2',
            'expert_ids' => 'nullable|array',
            'expert_ids.*' => 'exists:experts,id',
        ];
    }

    public function messages()
    {
        return [
            'live_end_time.after' => '结束时间必须晚于开始时间',
        ];
    }
}
