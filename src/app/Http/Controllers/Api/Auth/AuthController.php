<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginRequest;
use App\Repositories\Core\Auth\UserRepository;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return $this->respondWithToken($request->authenticate());
    }
    
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    public function verifyToken(Request $request)
    {
        if ($request->token) {
            try {
                return auth()->user()->load('roles', 'roles.permissions');
            } catch (\Exception $e) {
                return $this->respondUnAuthenticated($e->getMessage());
            }
        }
        return $this->respondFailedValidation(__('validation.required', ['attribute', 'Token']));
    }
    
    public function logout()
    {
        Auth::logout();
        return $this->respondNoContent();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL(),
        ], 200);
    }
}
