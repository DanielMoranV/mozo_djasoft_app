<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCharacteristic extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_characteristics';

    protected $fillable = [
        'name',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn($value) => strtolower($value),
            get: fn($value) => ucfirst($value)
        );
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_and_characteristics')
            ->withPivot('value');
    }
}
