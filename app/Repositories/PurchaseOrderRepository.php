<?php

namespace App\Repositories;

use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;

class PurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(PurchaseOrder $model)
    {
        parent::__construct($model);
    }

    public function getLastPurchaseOrder()
    {
        return $this->model->orderBy('created_at', 'desc')->first();
    }
}
