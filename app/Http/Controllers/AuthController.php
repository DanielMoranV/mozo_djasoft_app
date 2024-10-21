<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Classes\ApiResponseHelper;
use App\Http\Requests\AuthUserRequest;

class AuthController extends Controller
{
    protected $userRepository;
    private $relations = ['company', 'roles', 'parameter'];
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $company = auth()->user()->company_id;
            $data['company_id'] = $company;
            $user = $this->userRepository->store($data);

            if (!$user) {
                return ApiResponseHelper::sendResponse(null, 'Error al crear el usuario', 500);
            }


            $token = JWTAuth::fromUser($user);
            return ApiResponseHelper::sendResponse([
                'user' => new UserResource($user),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ], 'Usuario creado exitosamente', 201);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e, 'Error en el proceso de creación del usuario');
        }
    }

    public function login(AuthUserRequest $request)
    {
        // Run the custom validation method
        //$request->validateUser();

        $credentials = $request->only(['dni', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth()->user();
        $token = JWTAuth::fromUser($user);

        $user = $this->userRepository->getById($user->id, $this->relations);

        return ApiResponseHelper::sendResponse([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 'Datos del usuario autenticado');
    }

    public function logout()
    {
        auth()->logout(true);
        return ApiResponseHelper::sendResponse(null, 'Successfully logged out', 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
