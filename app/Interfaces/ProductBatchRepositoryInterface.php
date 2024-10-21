<?php

namespace App\Interfaces;

interface ProductBatchRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find an existing product batch based on product_id, price, and expiration_date.
     *
     * @param int $productId
     * @param float $price
     * @param string|null $expirationDate
     * @return mixed
     */
    public function findExistingBatch(int $productId, float $price, ?string $expirationDate);
}