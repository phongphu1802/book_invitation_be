<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\GetMeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RefreshRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class AuthController extends AbstractRestAPIController
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->loginRequest = LoginRequest::class;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = ['email' => $request->email, "password" => $request->password];

        $token = auth()->attempt($credentials, true);

        if (!$token)
            return $this->sendUnAuthorizedJsonResponse();

        return $this->sendOkJsonResponse(['data' => ['token_type' => config('jwt.jwt_type'), 'token' => $token, 'expires_in' => config('jwt.ttl') * 60]]);
    }

    /**
     * Create user and get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $credentials = ['email' => $request->email, "password" => $request->password];

        $token = auth()->attempt($credentials, true);

        return $this->sendOkJsonResponse(['user' => $user, 'data' => ['token_type' => config('jwt.jwt_type'), 'token' => $token, 'expires_in' => config('jwt.ttl') * 60]]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(LogoutRequest $request)
    {
        auth()->logout();

        return $this->sendOkJsonResponse(['message' => __('auth.logout')]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(GetMeRequest $request)
    {
        $user = auth()->user();

        if (!$user)
            return $this->sendUnAuthorizedJsonResponse();

        return $this->sendOkJsonResponse(['data' => UserResource::make($user)]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshRequest $request)
    {
        $user = auth()->user();

        if (!$user)
            return $this->sendUnAuthorizedJsonResponse();

        $tokenRefresh = auth()->refresh();

        return $this->sendOkJsonResponse(['data' => ['token_type' => config('jwt.jwt_type'), 'token' => $tokenRefresh, 'expires_in' => config('jwt.ttl') * 60]]);
    }
}