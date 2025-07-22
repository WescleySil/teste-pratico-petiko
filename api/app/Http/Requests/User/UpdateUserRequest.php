<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;
        $rules = [
            'name' => ['string'],
            'username' => ['string', 'unique:users,username,'.$userId],
            'email' => ['email', 'unique:users,email,'.$userId],
            'password' => ['string', 'min:8'],
        ];
        if ($this->user()->is_admin) {
            $rules['is_admin'] = ['boolean'];
        }

        return $rules;
    }
}
