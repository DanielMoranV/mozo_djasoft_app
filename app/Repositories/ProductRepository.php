<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}