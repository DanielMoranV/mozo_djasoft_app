<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    private $relations = ['company'];
    private WarehouseRepositoryInterface $warehouseRepositoryInterface;

    public function __construct(WarehouseRepositoryInterface $warehouseRepositoryInterface)
    {
        $this->warehouseRepositoryInterface = $warehouseRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->warehouseRepositoryInterface->getAll($this->relations);

        return ApiResponseHelper::sendResponse(WarehouseResource::collection($data), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $warehouse = $this->warehouseRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new WarehouseResource($warehouse), '', 200);
    }

    /**
     * t
     */
    public function store(StoreWarehouseRequest $request)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        DB::beginTransaction();
        try {
            $warehouse = $this->warehouseRepositoryInterface->delete($warehouse);
            DB::commit();
            return ApiResponseHelper::sendResponse(new WarehouseResource($warehouse), 'Record deleted succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
}