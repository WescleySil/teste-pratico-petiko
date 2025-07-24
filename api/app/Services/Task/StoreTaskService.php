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
        $user = User::find($data['user_id']);

        return $user->tasks()->create($data);
    }
}
