<?php

namespace App\Repositories;

use App\Interfaces\PurchaseOrderDetailRepositoryInterface;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderDetailRepository extends BaseRepository implements PurchaseOrderDetailRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(PurchaseOrderDetail $model)
    {
        parent::__construct($model);
    }
}
