<?php

namespace App\Services;

use App\Classes\ApiResponseHelper;
use App\Classes\DocumentNumberGenerator;
use App\Http\Requests\StorePurchaseOrderAndDetailsRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Interfaces\PurchaseOrderDetailRepositoryInterface;
use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    private PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface;
    private PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepositoryInterface;
    private DocumentNumberGenerator $documentNumberGenerator;
    public function __construct(PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface, PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepositoryInterface)
    {
        $this->purchaseOrderRepositoryInterface = $purchaseOrderRepositoryInterface;
        $this->purchaseOrderDetailRepositoryInterface = $purchaseOrderDetailRepositoryInterface;
        $this->documentNumberGenerator = new DocumentNumberGenerator('OC');
    }

    public function storePurchaseOrderAndDetails(StorePurchaseOrderAndDetailsRequest $request, array $relations = [])
    {
        try {
            DB::beginTransaction();
            $purchaseOrder = $this->storePurchaseOrder($request);
            $this->storePurchaseOrderDetails($request, $purchaseOrder);
            DB::commit();
            $response = $this->purchaseOrderRepositoryInterface->getById($purchaseOrder->id, $relations);
            return ApiResponseHelper::sendResponse(new PurchaseOrderResource($response), 'Orden de compra creada exitosamente', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    private function storePurchaseOrder(StorePurchaseOrderAndDetailsRequest $request): PurchaseOrder
    {
        $lastPurchaseOrder = $this->purchaseOrderRepositoryInterface->getLastPurchaseOrder();
        $number = $this->documentNumberGenerator->generate($lastPurchaseOrder);
        $amount = $this->calculateTotalAmount($request->input('purchase_order_details'));

        return $this->purchaseOrderRepositoryInterface->store([
            'number' => $number,
            'company_id' => $request->input('company_id'),
            'provider_id' => $request->input('provider_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'user_id' => $request->input('user_id'),
            'amount' => $amount,
            'expected_delivery' => $request->input('expected_delivery'),
            'notes' => $request->input('notes'),
        ]);
    }

    private function calculateTotalAmount(array $purchaseOrderDetails): float
    {
        return array_reduce($purchaseOrderDetails, function ($total, $detail) {
            return $total + ($detail['price'] * $detail['quantity']);
        }, 0);
    }

    private function storePurchaseOrderDetails($request, $purchaseOrder)
    {
        foreach ($request->input('purchase_order_details') as $detail) {
            $this->purchaseOrderDetailRepositoryInterface->store([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'expiration_date' => $detail['expiration_date'],
            ]);
        }
    }
}