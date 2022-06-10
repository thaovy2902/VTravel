<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    const ROLE_USER = 3;
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (!$token = auth()->attempt($credentials)) {
            return $this->respondError(config('message.login_error'), Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request)
    {
        $request['role_id'] = AuthController::ROLE_USER;
        $user = User::create($request->all());

        auth()->login($user);

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return $this->respondSuccess('');
    }

    public function me()
    {
        $token = JWTAuth::fromUser(auth()->user());

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'auth__token' => $token,
            'user' => auth()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 24 * 30 // 30 days
        ]);
    }
}
