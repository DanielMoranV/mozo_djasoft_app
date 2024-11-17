<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Resources\CategoryMovementResource;
use App\Interfaces\CategoryMovementRepositoryInterface;
use App\Http\Requests\StoreCategoryMovementRequest;
use App\Http\Requests\UpdateCategoryMovementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryMovementController extends Controller
{
    private CategoryMovementRepositoryInterface $categoryMovementRepositoryInterface;

    public function __construct(CategoryMovementRepositoryInterface $categoryMovementRepositoryInterface)
    {
        $this->categoryMovementRepositoryInterface = $categoryMovementRepositoryInterface;
    }

    public function index()
    {
        $data = $this->categoryMovementRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(CategoryMovementResource::collection($data), '', 200);
    }

    public function store(StoreCategoryMovementRequest $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];
        DB::beginTransaction();
        try {
            $categoryMovement = $this->categoryMovementRepositoryInterface->store($data);
            DB::commit();
            return ApiResponseHelper::sendResponse(new CategoryMovementResource($categoryMovement), 'Movimiento de categoría creado correctamente', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function show(string $id)
    {
        $data = $this->categoryMovementRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new CategoryMovementResource($data), '', 200);
    }

    public function update(UpdateCategoryMovementRequest $request, string $id)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];
        DB::beginTransaction();
        try {
            $categoryMovement = $this->categoryMovementRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse(new CategoryMovementResource($categoryMovement), 'Movimiento de categoría actualizado correctamente', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $categoryMovement = $this->categoryMovementRepositoryInterface->delete($id);
            DB::commit();
            return ApiResponseHelper::sendResponse(new CategoryMovementResource($categoryMovement), 'Movimiento de categoría eliminado correctamente', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
}