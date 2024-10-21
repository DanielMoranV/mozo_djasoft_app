<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function parameter()
    {
        return $this->hasMany(Parameter::class);
    }

    // public function productBatch()
    // {
    //     return $this->hasMany(ProductBatch::class, 'product_batches');
    // }
    public function stockMovement()
    {
        return $this->hasMany(StockMovement::class, 'stock_movements');
    }
}