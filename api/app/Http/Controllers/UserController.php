<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\DestroyUserService;
use App\Services\User\IndexUserService;
use App\Services\User\StoreUserService;
use App\Services\User\UpdateUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index(IndexUserRequest $request, IndexUserService $service): ResourceCollection
    {
        $user = Auth::user();
        $data = $request->validated();
        $users = $service->run($user, $data);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request, StoreUserService $service): JsonResponse
    {
        $user = $service->run($request->validated());

        return response()->json(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, UpdateUserService $service, User $user): JsonResponse
    {
        $user = $service->run($user, $request->validated());

        return response()->json(new UserResource($user));
    }

    public function destroy(DestroyUserService $service, User $user): JsonResponse
    {
        return response()->json($service->run($user));
    }
}
