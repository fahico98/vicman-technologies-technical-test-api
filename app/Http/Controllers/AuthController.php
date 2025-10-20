<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Exception;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(private UserRepository $user_repository)
    {}

    /**
     * Registra a un nuevo usuario.
     *
     * @param RegisterUserRequest $request Contenido de la petición HTTP entrante.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->user_repository->crear([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status'        => 'success',
            'message'       => 'User successfully registered',
            'user'          => $user,
            'token'       => $token,
            'token_type'  => 'Bearer'
        ], 201);
    }

    /**
     * Login and return auth token.
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'Login successful',
                'user'          => Auth::user()
            ]);
        }

        return response()->json([
            'status'        => 'error',
            'message'       => 'Unauthorized'
        ], 401);
    }

    /**
     * Get list of users (paginated) — protected route.
     */
    public function me()
    {
        try {
            $user = Auth::user();
            return response()->json(['user' => $user]);
        } catch (Exception $exception) {
            return response()->json([
                'status'        => 'error',
                'message'       => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user and revoke tokens — protected route.
     */
    public function logOut(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->tokens()->delete();

                return response()->json([
                    'response_code' => 200,
                    'status'        => 'success',
                    'message'       => 'Successfully logged out',
                ]);
            }

            return response()->json([
                'response_code' => 401,
                'status'        => 'error',
                'message'       => 'User not authenticated'
            ], 401);
        } catch (Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'An error occurred during logout'
            ], 500);
        }
    }
}
