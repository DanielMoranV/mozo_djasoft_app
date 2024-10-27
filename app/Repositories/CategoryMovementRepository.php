<?php

namespace App\Repositories;

use App\Interfaces\CategoryMovementRepositoryInterface;
use App\Models\CategoryMovement;

class CategoryMovementRepository extends BaseRepository implements CategoryMovementRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(CategoryMovement $model)
    {
        parent::__construct($model);
    }
}
