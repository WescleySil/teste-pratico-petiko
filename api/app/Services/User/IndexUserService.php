<?php

namespace App\Services\User;

use App\Models\User;

class IndexUserService
{
    public function run(User $user, array $data = [])
    {
        $name = $data['filters']['name'] ?? null;
        $username = $data['filters']['username'] ?? null;
        $email = $data['filters']['email'] ?? null;

        $query = User::query();
        if (! $user->is_admin) {
            $query->where('id', $user->id);
        } else {
            $query->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%'.$name.'%');
            });
            $query->when($username, function ($query) use ($username) {
                $query->where('username', 'like', '%'.$username.'%');
            });
            $query->when($email, function ($query) use ($email) {
                $query->where('email', 'like', '%'.$email.'%');
            });
        }

        return $query->get();
    }
}
