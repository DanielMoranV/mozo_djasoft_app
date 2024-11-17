<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StorePurchaseOrderAndDetailsRequest;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Services\PurchaseOrderService;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    private $relations = ['user', 'provider', 'warehouse', 'purchaseOrderDetail', 'company', 'purchaseOrderDetail.product'];
    private PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface;
    private PurchaseOrderService $purchaseOrderService;
    public function __construct(PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface, PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderRepositoryInterface = $purchaseOrderRepositoryInterface;
        $this->purchaseOrderService = $purchaseOrderService;
    }

    public function index()
    {
        $data = $this->purchaseOrderRepositoryInterface->getAll($this->relations);
        return ApiResponseHelper::sendResponse(PurchaseOrderResource::collection($data), '', 200);
    }

    public function show(string $id)
    {
        $purchaseOrder = $this->purchaseOrderRepositoryInterface->getById($id, $this->relations);
        return ApiResponseHelper::sendResponse(new PurchaseOrderResource($purchaseOrder), '', 200);
    }

    public function store(StorePurchaseOrderRequest $request)
    {

        $data = [
            'user_id' => $request->user_id,
            'provider_id' => $request->provider_id,
            'status' => $request->status,
            'expected_delivery' => $request->expected_delivery,
            'amount' => $request->amount,
            'warehouse_id' => $request->warehouse_id,
        ];

        DB::beginTransaction();

        try {
            $purchaseOrder = $this->purchaseOrderRepositoryInterface->store($data);

            $purchaseOrder = $this->purchaseOrderRepositoryInterface->getById($purchaseOrder->id);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function update(UpdatePurchaseOrderRequest $request, string $id)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $this->purchaseOrderRepositoryInterface->update($data, $id);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = $this->purchaseOrderRepositoryInterface->delete($id);
            DB::commit();
            return ApiResponseHelper::sendResponse(new PurchaseOrderResource($purchaseOrder), 'Record deleted succesful', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    public function storePurchaseOrderAndDetails(StorePurchaseOrderAndDetailsRequest $request)
    {
        return $this->purchaseOrderService->storePurchaseOrderAndDetails($request, $this->relations);
    }

    public function generatePdf(string $id)
    {
        $purchaseOrder = $this->purchaseOrderRepositoryInterface->getById($id, $this->relations);
        $pdf = app('dompdf.wrapper')->loadView('pdf.purchase_order', compact('purchaseOrder'));
        return $pdf->download('purchase_order_' . $purchaseOrder->number . '.pdf');
    }
}