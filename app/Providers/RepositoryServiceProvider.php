<?php

namespace App\Providers;

use App\Interfaces\CategoryMovementRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CompanyRepositoryInterface;
use App\Interfaces\MovementDetailRepositoryInterface;
use App\Interfaces\ParameterRepositoryInterface;
use App\Interfaces\ProductBatchRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\ProviderRepositoryInterface;
use App\Interfaces\PurchaseOrderDetailRepositoryInterface;
use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\StockMovementRepositoryInterface;
use App\Interfaces\UnitRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VoucherRepositoryInterface;
use App\Interfaces\WarehouseRepositoryInterface;
use App\repositories\CategoryMovementRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\MovementDetailRepository;
use App\repositories\ParameterRepository;
use App\Repositories\ProductBatchRepository;
use App\Repositories\ProductRepository;
use App\repositories\ProviderRepository;
use App\repositories\PurchaseOrderDetailRepository;
use App\repositories\PurchaseOrderRepository;
use App\Repositories\RoleRepository;
use App\Repositories\StockMovementRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use App\Repositories\VoucherRepository;
use App\repositories\WarehouseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->bind(MovementDetailRepositoryInterface::class, MovementDetailRepository::class);
        $this->app->bind(CategoryMovementRepositoryInterface::class, CategoryMovementRepository::class);
        $this->app->bind(ProductBatchRepositoryInterface::class, ProductBatchRepository::class);
        $this->app->bind(StockMovementRepositoryInterface::class, StockMovementRepository::class);
        $this->app->bind(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->bind(ProviderRepositoryInterface::class, ProviderRepository::class);
        $this->app->bind(ParameterRepositoryInterface::class, ParameterRepository::class);
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $this->app->bind(PurchaseOrderRepositoryInterface::class, PurchaseOrderRepository::class);
        $this->app->bind(PurchaseOrderDetailRepositoryInterface::class, PurchaseOrderDetailRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}