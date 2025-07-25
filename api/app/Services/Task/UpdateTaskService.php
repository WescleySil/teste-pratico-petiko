<?php

namespace App\Services\Task;

use App\Models\Task;

class UpdateTaskService
{
    public function run(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->load('user');
    }
}
