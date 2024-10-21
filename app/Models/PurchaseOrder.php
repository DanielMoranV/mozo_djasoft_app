<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'number',
        'user_id',
        'provider_id',
        'status',
        'expected_delivery',
        'company_id',
        'amount',
        'warehouse_id',
        'notes',
    ];

    protected function expectedDelivery(): Attribute
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

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrderDetail()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id', 'id');
    }
}