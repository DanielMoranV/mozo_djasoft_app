<?php

namespace App\Repositories;

use App\Interfaces\WarehouseRepositoryInterface;
use App\Models\Warehouse;
use App\Repositories\BaseRepository;

class WarehouseRepository extends BaseRepository implements WarehouseRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }
}
