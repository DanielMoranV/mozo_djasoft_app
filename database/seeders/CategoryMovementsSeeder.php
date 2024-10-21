<?php

namespace Database\Seeders;

use App\Models\CategoryMovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryMovementsSeeder extends Seeder
{
    public function run()
    {
        // Entradas
        CategoryMovement::create([
            'name' => 'Compra Nacional',
            'description' => 'Compra de productos nacionales',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Consignación Recibida',
            'description' => 'Consignación de productos recibida',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Donación',
            'description' => 'Donación de productos',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Entrada por Devolución del Cliente',
            'description' => 'Entrada de productos por devolución del cliente',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Ingreso Temporal',
            'description' => 'Ingreso temporal de productos',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Stock Inicial',
            'description' => 'Stock inicial de productos',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Transferencia de Almacén',
            'description' => 'Transferencia de productos entre almacenes',
            'type' => 'entrada',
        ]);

        CategoryMovement::create([
            'name' => 'Compra Internacional',
            'description' => 'Compra de productos internacionales',
            'type' => 'entrada',
        ]);

        // Salidas
        CategoryMovement::create([
            'name' => 'Venta',
            'description' => 'Venta de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Bonificación',
            'description' => 'Bonificación de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Premio',
            'description' => 'Premio de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Donación',
            'description' => 'Donación de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Retiro',
            'description' => 'Retiro de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Merma',
            'description' => 'Merma de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Salida Temporal',
            'description' => 'Salida temporal de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Devolución a Proveedor',
            'description' => 'Devolución de productos al proveedor',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Deterioro',
            'description' => 'Deterioro de productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Pérdida',
            'description' => 'Pérdida de productos',
            'type' => 'salida',
        ]);
        CategoryMovement::create([
            'name' => 'Salida por ajuste de stock',
            'description' => 'El stock se resta para ajustarse a la cantidad real de los productos',
            'type' => 'salida',
        ]);

        CategoryMovement::create([
            'name' => 'Ingreso por ajuste de stock',
            'description' => 'El stock se suma para ajustarse a la cantidad real de los productos',
            'type' => 'entrada',
        ]);
    }
}
