<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filters.name' => ['nullable', 'string'],
            'filters.username' => ['nullable', 'string'],
            'filters.email' => ['nullable', 'string', 'email'],
        ];
    }
}
