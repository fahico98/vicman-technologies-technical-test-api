<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $user = $this->user_repository->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'User successfully registered',
            'user'    => Auth::user()
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
            Auth::guard("web")->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'status'        => 'success',
                'message'       => 'Successfully logged out',
            ]);
        } catch (Exception $exception) {

            info(json_encode($exception->getTrace(), JSON_PRETTY_PRINT));

            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
