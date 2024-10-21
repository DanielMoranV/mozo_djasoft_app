<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovementsDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movements_detail';

    protected $fillable = [
        'product_batch_id',
        'stock_movement_id',
        'count',
    ];

    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function movementStock()
    {
        return $this->belongsTo(StockMovement::class);
    }
}
