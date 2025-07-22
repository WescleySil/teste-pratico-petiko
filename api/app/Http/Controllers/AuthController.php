<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\Auth\LoginService;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Realiza o login do usuário e retorna o token.
     */
    public function login(LoginRequest $request, LoginService $service): JsonResponse
    {
        $data = $request->validated();
        $token = $service->run($data);
        return response()->json($token, 200);
    }

    /**
     * Realiza o logout do usuário revogando o token atual.
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }
}
