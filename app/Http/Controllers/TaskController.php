<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\Task\DestroyTaskService;
use App\Services\Task\IndexTaskService;
use App\Services\Task\StoreTaskService;
use App\Services\Task\UpdateTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(IndexTaskRequest $request, IndexTaskService $service): ResourceCollection
    {
        $user = Auth::user();
        $tasks = $service->run($user, $request->validated());

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request, StoreTaskService $service): JsonResponse
    {
        $task = $service->run($request->validated());

        return response()->json(new TaskResource($task), 201);
    }

    public function update(UpdateTaskRequest $request, UpdateTaskService $service, Task $task): JsonResponse
    {
        $task = $service->run($task, $request->validated());

        return response()->json(new TaskResource($task));
    }

    public function destroy(DestroyTaskService $service, Task $task): JsonResponse
    {
        $service->run($task);

        return response()->json(null, 204);
    }
}
