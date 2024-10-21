<?php

namespace App\Repositories;

use App\Interfaces\VoucherRepositoryInterface;
use App\Models\Voucher;

class VoucherRepository extends BaseRepository implements VoucherRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Voucher $model)
    {
        parent::__construct($model);
    }
}
