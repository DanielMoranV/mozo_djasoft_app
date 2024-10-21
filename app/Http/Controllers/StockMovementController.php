<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\StoreEntryStockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Interfaces\MovementDetailRepositoryInterface;
use App\Interfaces\ProductBatchRepositoryInterface;
use App\Interfaces\StockMovementRepositoryInterface;
use App\Interfaces\VoucherRepositoryInterface;
use App\Models\ProductBatch;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    private $relations = ['user', 'provider', 'categoryMovement', 'movementDetails.productBatch.product', 'warehouse', 'voucher'];
    private StockMovementRepositoryInterface $stockMovementRepositoryInterface;
    private StockMovementService $stockMovementService;

    public function __construct(StockMovementRepositoryInterface $stockMovementRepositoryInterface, StockMovementService $stockMovementService)
    {
        $this->stockMovementRepositoryInterface = $stockMovementRepositoryInterface;
        $this->stockMovementService = $stockMovementService;
    }
    public function index()
    {
        $data = $this->stockMovementRepositoryInterface->getAll($this->relations)->sortByDesc('created_at');
        return ApiResponseHelper::sendResponse(StockMovementResource::collection($data), '', 200);
    }
    public function storeEntry(StoreEntryStockMovementRequest $request)
    {
        return $this->stockMovementService->storeEntry($request, $this->relations);
    }
}