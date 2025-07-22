<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Regras de validação para login.
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Validação customizada após as regras principais.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('username', $this->login)
                ->orWhere('email', $this->login)
                ->first();
            if ($user) {
                if (! auth()->attempt(['email' => $user->email, 'password' => $this->password])) {
                    $validator->errors()->add('login', 'Credenciais inválidas.');
                }
            } else {
                $validator->errors()->add('login', 'O usuário não existe.');
            }
        });
    }
}
