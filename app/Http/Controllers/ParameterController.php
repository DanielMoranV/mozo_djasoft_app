<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreParameterRequest;
use App\Http\Requests\UpdateParameterRequest;
use App\Http\Resources\ParameterResource;
use App\Interfaces\ParameterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParameterController extends Controller
{
    private $relations = ['company', 'warehouse', 'user'];
    private ParameterRepositoryInterface $parameterRepositoryInterface;

    public function __construct(ParameterRepositoryInterface $parameterRepositoryInterface)
    {
        $this->parameterRepositoryInterface = $parameterRepositoryInterface;
    }

    public function index()
    {
        $data = $this->parameterRepositoryInterface->getAll($this->relations);
        return ApiResponseHelper::sendResponse(ParameterResource::collection($data), '', 200);
    }

    public function show(string $id)
    {
        $parameter = $this->parameterRepositoryInterface->getById($id, $this->relations);
        return ApiResponseHelper::sendResponse(new ParameterResource($parameter), '', 200);
    }

    public function store(StoreParameterRequest $request)
    {
        $data = [
            'warehouse_id' => $request->warehouse_id,
            'sunat_send' => $request->sunat_send,
            'locked' => $request->locked,
            'user_id' => $request->user_id,
            'company_id' => $request->company_id,
        ];
        DB::beginTransaction();

        try {
            $parameter = $this->parameterRepositoryInterface->store($data);

            $parameter = $this->parameterRepositoryInterface->getById($parameter->id, $this->relations);

            DB::commit();
            return ApiResponseHelper::sendResponse(new ParameterResource($parameter), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function update(UpdateParameterRequest $request, string $id)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $this->parameterRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse(
                null,
                'Record updated succesful',
                200
            );
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $parameter = $this->parameterRepositoryInterface->delete($id);
            DB::commit();
            return ApiResponseHelper::sendResponse(new ParameterResource($parameter), 'Record deleted succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
}