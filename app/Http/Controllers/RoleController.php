<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function assignRole(Request $request)
    {

        $request->validate([
            'dni' => 'required|exists:users,dni',
            'role_name' => 'required|string|exists:roles,name'
        ]);
        Log::info($request->all());

        try {
            $user = $this->userRepository->findByDni($request->dni);
            $user->syncRoles([]);
            $role = Role::where('name', $request->role_name)->firstOrFail();
            $user->assignRole($role);
            Log::info($user->roles);
            return ApiResponseHelper::sendResponse(['message' => 'Role assigned successfully']);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e);
        }
    }

    public function removeRole(Request $request)
    {
        $request->validate([
            'dni' => 'required|exists:users,dni',
            'role_name' => 'required|string|exists:roles,name'
        ]);

        try {
            $user = $this->userRepository->findByDni($request->dni);
            $role = Role::where('name', $request->role_name)->firstOrFail();
            $user->removeRole($role);

            return ApiResponseHelper::sendResponse(['message' => 'Role removed successfully']);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e);
        }
    }

    public function update(UpdateRoleRequest $request)
    {
        $request->validated();
        try {
            $role = Role::where('id', $request->id)->firstOrFail();
            $role->update($request->validated());
            return ApiResponseHelper::sendResponse($role, 'Role updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e);
        }
    }

    public function getRoles()
    {
        try {
            $roles = Role::all();
            return ApiResponseHelper::sendResponse($roles);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e);
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create($request->validated());
            return ApiResponseHelper::sendResponse($role, 'Role created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseHelper::rollback($e);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::where('id', $id)->firstOrFail();
            $role->delete();
            DB::commit();
            return ApiResponseHelper::sendResponse(new RoleResource($role), 'Role deleted successfully', 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::rollback($e);
        }
    }
}