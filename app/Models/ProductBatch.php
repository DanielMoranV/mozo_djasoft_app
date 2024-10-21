<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Exception;

class ProductBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_batches';

    protected $fillable = [
        'product_id',
        'batch_number',
        'expiration_date',
        'price',
        'quantity',
        //'warehouse_id'
    ];

    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            // Accesor: Formatea la fecha cuando se obtiene de la base de datos.
            get: function ($value) {
                try {
                    return $value ? Carbon::parse($value)->format('Y-m-d') : null;
                } catch (Exception $e) {
                    return null; // Devolver null si ocurre un error
                }
            },
            // Mutador: Convierte la fecha al formato Y-m-d antes de guardarla o guarda null.
            set: function ($value) {
                if (empty($value)) {
                    return null;
                }
                try {
                    return Carbon::parse($value)->format('Y-m-d');
                } catch (Exception $e) {
                    return null; // Guardar null si la fecha no es válida
                }
            }
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function warehouse()
    // {
    //     return $this->belongsTo(Warehouse::class);
    // }
}