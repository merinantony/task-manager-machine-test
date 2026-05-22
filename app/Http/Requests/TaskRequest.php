<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}
