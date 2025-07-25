<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $task = $this->route('task');
        if (!$user) {
            return false;
        }
        if ($user->is_admin) {
            return true;
        }
        return $task && $task->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'overdue', 'completed'])],
            'due_date' => ['sometimes', 'date', 'after_or_equal:today'],
        ];
    }
}
