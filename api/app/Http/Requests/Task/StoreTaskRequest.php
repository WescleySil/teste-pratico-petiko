<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'user_id' => ['nullable', 'integer', 'exists:users,id,deleted_at,NULL']
        ];
    }
}
