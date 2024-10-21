<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\StoreUnitsRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Interfaces\UnitRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    private UnitRepositoryInterface $unitRepositoryInterface;

    public function __construct(UnitRepositoryInterface $unitRepositoryInterface)
    {
        $this->unitRepositoryInterface = $unitRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->unitRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(UnitResource::collection($data), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = $this->unitRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new UnitResource($unit), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $data = [
            'name' => $request->input('name'),
            'symbol' => $request->input('symbol'),
        ];
        DB::beginTransaction();
        try {
            $unit = $this->unitRepositoryInterface->store($data);
            DB::commit();
            return ApiResponseHelper::sendResponse(new UnitResource($unit), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function storeUnits(StoreUnitsRequest $request)
    {
        $unitsData = $request->input('units');
        $successfulRecords = [];
        $failedRecords = [];

        DB::beginTransaction();
        try {
            foreach ($unitsData as $unitData) {
                try {
                    $data = [
                        'name' => $unitData['name'],
                        'symbol' => $unitData['symbol'],
                    ];
                    $this->unitRepositoryInterface->store($data);
                    $successfulRecords[] = $data;
                } catch (Exception $e) {
                    $failedRecords[] = array_merge($unitData, ['error' => $e->getMessage()]);
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
    public function update(UpdateUnitRequest $request, string $id)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $this->unitRepositoryInterface->update($data, $id);
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
        $this->unitRepositoryInterface->delete($id);
        return ApiResponseHelper::sendResponse('', 'Record delete succesful', 200);
    }
}
