<?php

namespace App\Interfaces;

interface PurchaseOrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getLastPurchaseOrder();
}