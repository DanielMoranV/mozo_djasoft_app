<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CompanyResource;
use App\Interfaces\CategoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->categoryRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(CategoryResource::collection($data), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new CompanyResource($category), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];
        DB::beginTransaction();
        try {
            $category = $this->categoryRepositoryInterface->store($data);
            DB::commit();
            return ApiResponseHelper::sendResponse(new CategoryResource($category), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function StoreCategories(StoreCategoriesRequest $request)
    {
        $categoriesData = $request->input('categories');
        $successfulRecords = [];
        $failedRecords = [];

        DB::beginTransaction();
        try {
            foreach ($categoriesData as $categoryData) {
                try {
                    $data = [
                        'name' => $categoryData['name'],
                        'description' => $categoryData['description'],
                    ];
                    $this->categoryRepositoryInterface->store($data);
                    $successfulRecords[] = $data;
                } catch (Exception $e) {
                    $failedRecords[] = array_merge($categoryData, ['error' => $e->getMessage()]);
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
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $this->categoryRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse('', 'Record update succesful', 200);
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
        $this->categoryRepositoryInterface->delete($id);
        return ApiResponseHelper::sendResponse('', 'Record delete succesful', 200);
    }
}
