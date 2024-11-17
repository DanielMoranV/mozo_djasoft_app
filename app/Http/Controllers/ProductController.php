<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $relations = ['category', 'unit', 'user'];
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productRepositoryInterface->getAll($this->relations);
        return ApiResponseHelper::sendResponse(ProductResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id
        ];

        DB::beginTransaction();

        try {
            $product = $this->productRepositoryInterface->store($data);
            $product->load('category', 'unit');
            DB::commit();
            return ApiResponseHelper::sendResponse(new ProductResource($product), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
    public function storeProducts(StoreProductsRequest $request)
    {
        $productsData = $request->input('products');
        $successfulRecords = [];
        $failedRecords = [];

        DB::beginTransaction();
        try {
            foreach ($productsData as $productData) {
                try {
                    $data = [
                        'code' => $productData['code'],
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'category_id' => $productData['category_id'],
                        'unit_id' => $productData['unit_id'],
                        'user_id' => $productData['user_id']
                    ];

                    $newProduct = $this->productRepositoryInterface->store($data);

                    $dataProduct = $this->productRepositoryInterface->getById($newProduct->id, $this->relations);
                    $successfulRecords[] = new ProductResource($dataProduct);
                } catch (Exception $e) {
                    $failedRecords[] = array_merge($productData, ['error' => $e->getMessage()]);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new ProductResource($product), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $this->productRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse(null, 'Record updated succesful', 200);
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
        DB::beginTransaction();
        try {
            $product = $this->productRepositoryInterface->delete($id);
            DB::commit();
            return ApiResponseHelper::sendResponse(new ProductResource($product), 'Record deleted succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
}