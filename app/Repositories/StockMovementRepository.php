<?php

namespace App\Repositories;

use App\Interfaces\StockMovementRepositoryInterface;
use App\Models\StockMovement;

class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(StockMovement $model)
    {
        parent::__construct($model);
    }
}
