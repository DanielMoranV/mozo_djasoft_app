<?php

namespace App\Repositories;

use App\Interfaces\ProductBatchRepositoryInterface;
use App\Models\ProductBatch;

class ProductBatchRepository extends BaseRepository implements ProductBatchRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(ProductBatch $model)
    {
        parent::__construct($model);
    }

    /**
     * Check if a product batch exists based on product_id, price, and expiration_date.
     *
     * @param int $productId
     * @param float $price
     * @param string|null $expirationDate
     * @return ProductBatch|null
     */
    public function findExistingBatch(int $productId, float $price, ?string $expirationDate)
    {
        $query = $this->model->where('product_id', $productId)
            ->where('price', $price);

        if (is_null($expirationDate)) {
            $query->whereNull('expiration_date');
        } else {
            $query->where('expiration_date', $expirationDate);
        }

        return $query->first();
    }
}
