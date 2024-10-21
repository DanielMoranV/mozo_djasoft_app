<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Interfaces\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    private CompanyRepositoryInterface $companyRepositoryInterface;

    public function __construct(CompanyRepositoryInterface $companyRepositoryInterface)
    {
        $this->companyRepositoryInterface = $companyRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->companyRepositoryInterface->getAll();
        return ApiResponseHelper::sendResponse(CompanyResource::collection($data), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = $this->companyRepositoryInterface->getById($id);
        return ApiResponseHelper::sendResponse(new CompanyResource($company), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data =
            [
                'company_name' => $request->company_name,
                'ruc' => $request->ruc,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'logo_path' => $request->logo_path,
                'sol_user' => $request->sol_user,
                'sol_pass' => $request->sol_pass,
                'cert_path' => $request->cert_path,
                'client_id' => $request->cliente_id,
                'client_secret' => $request->client_secret,
                'production' => $request->production,
            ];
        DB::beginTransaction();
        try {
            $company = $this->companyRepositoryInterface->store($data);
            DB::commit();
            return ApiResponseHelper::sendResponse(new CompanyResource($company), 'Record create succesful', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, string $id)
    {
        // $data =
        //     [
        //         'company_name' => $request->company_name,
        //         'ruc' => $request->ruc,
        //         'address' => $request->address,
        //         'email' => $request->email,
        //         'phone' => $request->phone,
        //         'status' => $request->status,
        //         'logo_path' => $request->logo_path,
        //         'sol_user' => $request->sol_user,
        //         'sol_pass' => $request->sol_pass,
        //         'cert_path' => $request->cert_path,
        //         'client_id' => $request->cliente_id,
        //         'client_secret' => $request->client_secret,
        //         'production' => $request->production,
        //     ];
        $data = $request->all();

        DB::beginTransaction();
        try {
            $this->companyRepositoryInterface->update($data, $id);
            DB::commit();
            return ApiResponseHelper::sendResponse(null, 'Record updated succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }
    public function logo(Request $request, string $id)
    {
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            $data['logo_path']  = $path;
        }

        DB::beginTransaction();
        try {
            $this->companyRepositoryInterface->update($data, $id);
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
        $this->companyRepositoryInterface->delete($id);
        return ApiResponseHelper::sendResponse(null, 'Record deleted succesful', 200);
    }
}
