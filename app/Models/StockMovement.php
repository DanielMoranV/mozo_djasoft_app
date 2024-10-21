<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Exception;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stock_movements';
    protected $fillable = [
        'user_id',
        'date',
        'type',
        'comment',
        'category_movements_id',
        'provider_id',
        'warehouse_id',
        'voucher_id',
    ];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                try {
                    return Carbon::parse($value)->format('Y-m-d');
                } catch (Exception $e) {
                    return $value;
                }
            },
            set: function ($value) {
                try {
                    return Carbon::parse($value)->format('Y-m-d');
                } catch (Exception $e) {
                    return null;
                }
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryMovement()
    {
        return $this->belongsTo(CategoryMovement::class, 'category_movements_id');
    }

    public function movementDetails()
    {
        return $this->hasMany(MovementsDetail::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}