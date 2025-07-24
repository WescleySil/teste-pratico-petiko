<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filters.title' => ['nullable', 'string'],
            'filters.status' => ['nullable', 'in:pending,overdue,completed'],
            'filters.user_id' => ['nullable', 'integer', 'exists:users,id,deleted_at,NULL'],
        ];
    }
}
