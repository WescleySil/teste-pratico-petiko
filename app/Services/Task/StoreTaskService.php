<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;

class StoreTaskService
{
    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function run(array $data): Task
    {
        $data['status'] = 'pending';

        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        $user = User::find($data['user_id']);

        $task = $user->tasks()->create($data);
        return $task->load('user');
    }
}
