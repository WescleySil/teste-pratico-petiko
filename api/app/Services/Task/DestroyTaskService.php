<?php

namespace App\Services\Task;

use App\Models\Task;

class DestroyTaskService
{
    public function run(Task $task): bool
    {
        return $task->delete();
    }
}
