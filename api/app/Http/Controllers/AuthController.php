<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(LoginRequest $request, LoginService $service): JsonResponse
    {
        $data = $request->validated();
        $result = $service->run($data);

        return response()->json($result, 200);
    }


    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }


    public function me(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'data' => $user
        ]);
    }
}
