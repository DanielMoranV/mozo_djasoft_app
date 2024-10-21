<?php

namespace App\Services;

use App\Classes\ApiResponseHelper;
use App\Constants\DatabaseErrorCodes;
use App\Http\Requests\StoreEntryStockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Interfaces\MovementDetailRepositoryInterface;
use App\Interfaces\ProductBatchRepositoryInterface;
use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Interfaces\StockMovementRepositoryInterface;
use App\Interfaces\VoucherRepositoryInterface;
use App\Models\ProductBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockMovementService
{
    private StockMovementRepositoryInterface $stockMovementRepositoryInterface;
    private VoucherRepositoryInterface $voucherRepositoryInterface;
    private ProductBatchRepositoryInterface $productBatchRepositoryInterface;
    private MovementDetailRepositoryInterface $movementDetailRepositoryInterface;
    private PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface;

    public function __construct(
        StockMovementRepositoryInterface $stockMovementRepositoryInterface,
        VoucherRepositoryInterface $voucherRepositoryInterface,
        ProductBatchRepositoryInterface $productBatchRepositoryInterface,
        MovementDetailRepositoryInterface $movementDetailRepositoryInterface,
        PurchaseOrderRepositoryInterface $purchaseOrderRepositoryInterface
    ) {
        $this->stockMovementRepositoryInterface = $stockMovementRepositoryInterface;
        $this->voucherRepositoryInterface = $voucherRepositoryInterface;
        $this->productBatchRepositoryInterface = $productBatchRepositoryInterface;
        $this->movementDetailRepositoryInterface = $movementDetailRepositoryInterface;
        $this->purchaseOrderRepositoryInterface = $purchaseOrderRepositoryInterface;
    }

    public function storeEntry(StoreEntryStockMovementRequest $request, $relations)
    {
        DB::beginTransaction();

        try {
            $voucher = $this->createVoucher($request->input('voucher'));
            $purchaseOrderId = $request->input('purchaseOrder_id');

            if ($purchaseOrderId !== null && $purchaseOrderId !== '') {
                $this->updatePurchaseOrder($purchaseOrderId);
            }
            $stockMovement = $this->createStockMovement($request, $voucher);
            $this->processMovementDetails($request->input('movements_details'), $stockMovement);

            $stockMovement = $this->stockMovementRepositoryInterface->getById($stockMovement->id, $relations);
            DB::commit();
            return ApiResponseHelper::sendResponse(new StockMovementResource($stockMovement), 'Entry created successfully.', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::rollback($ex);
        }
    }

    private function createVoucher(array $voucherData)
    {
        // Limpiar la serie eliminando espacios en blanco
        $cleanSeries = trim($voucherData['series']);

        // Verificar que la serie no esté vacía después de limpiar
        if (empty($cleanSeries)) {
            throw new \Exception('La serie no puede estar vacía');
        }

        // Convertir el primer carácter de la serie a mayúsculas
        $seriesFirstChar = strtoupper(substr($cleanSeries, 0, 1));

        // Inicializar el tipo de voucher
        $type = null;

        // Verificar si la serie empieza con 'F' o 'B'
        if ($seriesFirstChar === 'F') {
            $type = 'factura';
        } elseif ($seriesFirstChar === 'B') {
            $type = 'boleta';
        } else {
            $type = 'ticket';
        }

        // Pasar los datos al repositorio, incluyendo el tipo
        return $this->voucherRepositoryInterface->store([
            'series' => $cleanSeries,
            'number' => $voucherData['number'],
            'amount' => $voucherData['amount'],
            'status' => $voucherData['status'],
            'issue_date' => $voucherData['issue_date'],
            'type' => $type,  // Añadir el tipo de voucher (factura, boleta o ticket)
        ]);
    }

    private function createStockMovement($request, $voucher)
    {
        return $this->stockMovementRepositoryInterface->store([
            'user_id' => $request->input('user_id'),
            'comment' => $request->input('comment'),
            'category_movements_id' => $request->input('category_movements_id'),
            'voucher_id' => $voucher->id,
            'provider_id' => $request->input('provider_id'),
            'warehouse_id' => $request->input('warehouse_id'),
        ]);
    }

    private function processMovementDetails($movementsDetails, $stockMovement)
    {
        foreach ($movementsDetails as $detail) {
            $this->handleProductBatch($detail, $stockMovement);
        }
    }

    private function updatePurchaseOrder($purchaseOrder)
    {
        $data = [
            'status' => 'Aprobado'
        ];
        $this->purchaseOrderRepositoryInterface->update($data, $purchaseOrder);
    }

    private function handleProductBatch(array $detail, $stockMovement)
    {
        // Usamos el repositorio para buscar un lote existente
        $productBatch = $this->productBatchRepositoryInterface->findExistingBatch(
            $detail['product_id'],
            $detail['price'],
            $detail['expiration_date']
        );

        if ($productBatch) {
            // Si ya existe el lote, simplemente actualizamos la cantidad
            $productBatch->quantity += $detail['count'];
            $productBatch->save();
        } else {
            // Si no existe, creamos un nuevo lote
            $dataProductBatch = [
                'product_id' => $detail['product_id'],
                'batch_number' => ProductBatch::where('product_id', $detail['product_id'])->count() + 1,
                'expiration_date' => $detail['expiration_date'],
                'price' => $detail['price'],
                'quantity' => $detail['count'],
            ];

            try {
                // Intentamos crear el nuevo lote
                $productBatch = $this->productBatchRepositoryInterface->store($dataProductBatch);
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('General error DB: ' . $e->getCode());
                throw $e;  // Manejamos cualquier otro tipo de error
            }
        }

        // Guardar el detalle del movimiento de stock
        $dataMovementDetail = [
            'stock_movement_id' => $stockMovement->id,
            'product_batch_id' => $productBatch->id,
            'count' => $detail['count']
        ];

        $this->movementDetailRepositoryInterface->store($dataMovementDetail);
    }
}