<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseOrder = PurchaseOrder::create([
            'number' => 'OC-20241010-0001',
            'company_id' => 1,
            'user_id' => 1,
            'status' => 'Pendiente',
            'warehouse_id' => 1,
            'provider_id' => 1,
            'expected_delivery' => '2024-12-31',
            'amount' => 1000,
            'notes' => 'Nota de compra',
        ]);

        $idPurchaseOrder = $purchaseOrder->id;

        $details = [
            [
                'product_id' => 1,
                'expiration_date' => '2024-12-31',
                'price' => 1000,
                'quantity' => 1,
            ],
            [
                'product_id' => 2,
                'expiration_date' => '2024-12-31',
                'price' => 2000,
                'quantity' => 2,
            ],
            [
                'product_id' => 3,
                'expiration_date' => '2024-12-31',
                'price' => 3000,
                'quantity' => 3,
            ]

        ];

        foreach ($details as $detail) {
            PurchaseOrderDetail::create([
                'purchase_order_id' => $idPurchaseOrder,
                'product_id' => $detail['product_id'],
                'expiration_date' => $detail['expiration_date'],
                'price' => $detail['price'],
                'quantity' => $detail['quantity'],
            ]);
        }
    }
}