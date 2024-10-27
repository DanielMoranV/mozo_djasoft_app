<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'ruc',
        'address',
        'email',
        'phone',
        'status',
        'logo_path',
        'sol_user',
        'sol_pass',
        'cert_path',
        'client_id',
        'client_secret',
        'production',
    ];

    // protected function company_name(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn($value) => strtolower($value),
    //         get: fn($value) => ucfirst($value)
    //     );
    // }


    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }
    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
