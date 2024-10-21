<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsAndCharacteristics extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products_and_characteristics';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productCharacteristic()
    {
        return $this->belongsTo(ProductCharacteristic::class);
    }
}