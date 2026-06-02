<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExpertRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'hospital' => 'required|string|max:200',
            'department' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        return $rules;
    }
}
