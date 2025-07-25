<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;

class IndexTaskService
{
    public function run(User $user, array $data = [])
    {
        $title = $data['filters']['title'] ?? null;
        $status = $data['filters']['status'] ?? null;
        $userId = $data['filters']['user_id'] ?? null;

        $query = Task::query();

        if (! $user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            $query->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }

        $query->when($title, function ($query) use ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        });

        $query->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        });

        return $query->with('user')->get();
    }
}
