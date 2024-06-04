<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Enums\RoleEnum;
use App\Http\Requests\GetMeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RefreshRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;

class AuthController extends AbstractRestAPIController
{
    protected $userResource, $roleService;
    public function __construct(UserService $service, RoleService $roleService)
    {
        $this->service = $service;
        $this->loginRequest = LoginRequest::class;
        $this->userResource = UserResource::class;
        $this->roleService = $roleService;
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

        $refreshToken = auth()->setTTL(config('jwt.refresh_ttl'))->attempt($credentials, true);

        return $this->sendOkJsonResponse([
            'data' => [
                'token_type' => config('jwt.jwt_type'),
                'token' => $token,
                'refresh_token' => $refreshToken,
                'expires_in' => config('jwt.ttl') * 60
            ]
        ]);
    }

    /**
     * Create user and get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role_uuid' => $this->roleService->findOneWhere(['name' => RoleEnum::USER->value])->getKey(),
        ]);

        $credentials = ['email' => $request->email, "password" => $request->password];

        $token = auth()->attempt($credentials, true);

        $refreshToken = auth()->setTTL(config('jwt.refresh_ttl'))->attempt($credentials, true);

        return $this->sendOkJsonResponse([
            'data' => [
                'token_type' => config('jwt.jwt_type'),
                'token' => $token,
                'refresh_token' => $refreshToken,
                'expires_in' => config('jwt.ttl') * 60,
                'user' => $user,
            ]
        ]);
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

        return $this->sendOkJsonResponse($this->service->resourceToData($this->userResource, $user));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshRequest $request)
    {
        $isStillIn = auth()->setToken($request->token)->check();

        if (!$isStillIn) {
            return $this->sendUnAuthorizedJsonResponse();
        }

        $token = auth()->refresh();

        return $this->sendOkJsonResponse([
            'data' =>
                [
                    'token_type' => config('jwt.jwt_type'),
                    'token' => $token,
                    'expires_in' => config('jwt.ttl') * 60
                ]
        ]);
    }
}