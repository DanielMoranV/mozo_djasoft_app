<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $relations = ['company', 'roles', 'parameters'];
    private UserRepositoryInterface $userRepositoryInterface;
    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userRepositoryInterface->getAll($this->relations);
        return ApiResponseHelper::sendResponse(UserResource::collection($data), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userRepositoryInterface->getById($id, $this->relations);
        return ApiResponseHelper::sendResponse(new UserResource($user), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'dni' => $request->dni,
            'phone' => $request->phone,
            'url_photo_profile' => $request->url_photo_profile,
            'email' => $request->email,
            'password' => $request->password,
            'company_id' => $request->company_id,
        ];

        // Set company_id from the authenticated user if not provided
        if (empty($request->company_id)) {
            $data['company_id'] = auth()->user()->company_id;
        }

        DB::beginTransaction();
        try {
            // Store user data
            $user = $this->userRepositoryInterface->store($data);

            // Assign role if provided
            if (!empty($request->role)) {
                $role = Role::where('name', $request->role)->firstOrFail();
                $user->assignRole($role);
            }

            DB::commit();

            // Emitir evento de usuario creado
            event(new UserCreated($user));


            return ApiResponseHelper::sendResponse($user, 'Record created successfully', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
    public function storeUsers(StoreUsersRequest $request)
    {
        $usersData = $request->input('users'); // Obtener los datos de los usuarios
        $successfulRecords = [];
        $failedRecords = [];

        DB::beginTransaction();
        try {
            foreach ($usersData as $userData) {
                try {
                    // Preparar datos
                    $data = [
                        'name' => $userData['name'],
                        'dni' => $userData['dni'],
                        'phone' => $userData['phone'],
                        'url_photo_profile' => $userData['url_photo_profile'] ?? null,
                        'email' => $userData['email'],
                        'password' => bcrypt($userData['password']),
                        'company_id' => $userData['company_id'] ?? auth()->user()->company_id,
                    ];
                    // Crear usuario
                    $user = $this->userRepositoryInterface->store($data);

                    // Asignar rol
                    if (!empty($userData['role'])) {
                        $role = Role::where('name', $userData['role'])->firstOrFail();
                        $user->assignRole($role);
                    }
                    $dataUser = $this->userRepositoryInterface->getById($user->id, $this->relations);

                    // Agregar registro exitoso
                    $successfulRecords[] = new UserResource($dataUser);
                } catch (Exception $e) {
                    // Registrar error para el usuario actual
                    $failedRecords[] = array_merge($userData, ['error' => $e->getMessage()]);
                }
            }

            DB::commit();

            // Construir respuesta
            $response = [
                'success' => $successfulRecords,
                'errors' => $failedRecords,
                'message' => 'Processing complete'
            ];

            return ApiResponseHelper::sendResponse($response, 'Records processed', 200);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Error processing records: ' . $ex->getMessage());
            return ApiResponseHelper::rollback($ex);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $user = $this->userRepositoryInterface->update($data, $id);
            DB::commit();

            // Cargar relaciones del usuario actualizado
            $userWithRelations = $this->userRepositoryInterface->getById($id, $this->relations);

            // Emitir evento de usuario actualizado
            broadcast(new UserUpdated($userWithRelations))->toOthers();

            return ApiResponseHelper::sendResponse(new UserResource($userWithRelations), 'Record updated successful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function photoProfile(Request $request, string $id)
    {
        $data = $request->all();

        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $path = $file->store('profile_photos', 'public');
            $data['url_photo_profile']  = $path;
        }

        DB::beginTransaction();
        try {
            $this->userRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse($data, 'Record updated succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->userRepositoryInterface->deleteUser($id);

            Log::info($result);

            if ($result['status'] === 'disabled') {
                // Emitir evento de usuario deshabilitado
                //event(new UserDeleted($id, 'disabled'));
                return ApiResponseHelper::sendResponse(null, 'Usuario deshabilitado exitosamente', 200);
            } elseif ($result['status'] === 'deleted') {
                // Emitir evento de usuario eliminado
                // event(new UserDeleted($id, 'deleted'));
                return ApiResponseHelper::sendResponse(null, 'Usuario eliminado exitosamente', 200);
            }
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e, 'Error al eliminar el usuario');
        }
    }

    public function restore(string $id)
    {
        $this->userRepositoryInterface->restore($id);
        return ApiResponseHelper::sendResponse(null, 'Record restore succesful', 200);
    }
}