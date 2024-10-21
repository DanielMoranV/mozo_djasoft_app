<?php

namespace App\repositories;

use App\Interfaces\ProviderRepositoryInterface;
use App\Models\Provider;
use App\Repositories\BaseRepository;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Provider $model)
    {
        parent::__construct($model);
    }
}