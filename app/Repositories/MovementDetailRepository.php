<?php

namespace App\Repositories;

use App\Interfaces\MovementDetailRepositoryInterface;
use App\Models\MovementsDetail;

class MovementDetailRepository extends BaseRepository implements MovementDetailRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(MovementsDetail $model)
    {
        parent::__construct($model);
    }
}
