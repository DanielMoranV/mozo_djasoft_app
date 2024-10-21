<?php

namespace App\repositories;

use App\Interfaces\ParameterRepositoryInterface;
use App\Models\Parameter;
use App\Repositories\BaseRepository;

class ParameterRepository extends BaseRepository implements ParameterRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Parameter $model)
    {
        parent::__construct($model);
    }
}
