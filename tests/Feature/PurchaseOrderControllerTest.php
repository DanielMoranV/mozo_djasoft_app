<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderControllerTest extends TestCase
{


    public function test_store_purchase_order_and_details()
    {
        $data = [
            'provider_id' => 1,
            'company_id' => 1,
            'user_id' => 1,
            'notes' => 'Test notes',
            'warehouse_id' => 1,
            'expected_delivery' => now()->format('Y-m-d'),
            'amount' => 100.00,

            'purchase_order_details' => [
                [
                    'product_id' => 1,
                    'price' => 10.00,
                    'quantity' => 5
                ],
                [
                    'product_id' => 2,
                    'price' => 20.00,
                    'quantity' => 5
                ],
                [
                    'product_id' => 3,
                    'price' => 13.00,
                    'quantity' => 10,
                    'expiration_date' => now()->addYear()->format('Y-m-d')
                ]
            ]
        ];

        $response = $this->postJson('/api/purchase-orders/store-purchase-order-and-details', $data);

        // Imprimir la respuesta en la consola
        echo "Respuesta de la API:\n";
        echo json_encode($response, JSON_PRETTY_PRINT) . "\n";

        // $response->assertStatus(Response::HTTP_CREATED)
        //     ->assertJson(['message' => 'Orden de compra creada exitosamente']);



        // Verificar que la orden de compra se guardó correctamente
        $this->assertDatabaseHas('purchase_orders', [
            'notes' => 'Test notes',
            'provider_id' => 1,
            'company_id' => 1,
            'user_id' => 1,
            'warehouse_id' => 1,
            'expected_delivery' => now()->format('Y-m-d'),
        ]);

        // Obtener la orden de compra recién creada
        $purchaseOrder = PurchaseOrder::latest('id')->first();

        // Verificar que los detalles de la orden de compra se guardaron correctamente
        foreach ($data['purchase_order_details'] as $detail) {
            $this->assertDatabaseHas('purchase_order_details', [
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $detail['product_id'],
                'price' => $detail['price'],
                'count' => $detail['count'],
            ]);
        }

        // Verificar que el número total de detalles es correcto
        $this->assertEquals(
            count($data['purchase_order_details']),
            PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)->count()
        );
    }
}