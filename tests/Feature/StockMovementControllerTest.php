<?php

namespace Tests\Feature;

use App\Models\ProductBatch;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Tests\TestCase;

class StockMovementControllerTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function it_can_store_a_stock_movement_entry()
    {
        $voucherData = [
            'series' => 'F001',
            'number' => '12345',
            'amount' => 1000,
            'status' => 'pagado',
            'issue_date' => now()->format('Y-m-d H:i:s'),
        ];

        $requestPayload = [
            'voucher' => $voucherData,
            'user_id' => 1,
            'comment' => 'New stock entry',
            'category_movements_id' => 1,
            'provider_id' => 1,
            'warehouse_id' => 1,
            'movements_details' => [
                [
                    'product_id' => 1,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 10.00,
                    'count' => 5
                ],
                [
                    'product_id' => 2,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 20.00,
                    'count' => 5
                ],
                [
                    'product_id' => 3,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 13.00,
                    'count' => 10
                ]
            ]
        ];

        $response = $this->postJson('/api/stock-movements/store-entry', $requestPayload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'user_id',
                    'comment',
                    'category_movements_id',
                    'voucher_id',
                    'created_at',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('stock_movements', [
            'user_id' => 1,
            'comment' => 'New stock entry',
            'category_movements_id' => 1,
        ]);

        // Check that product batches are created or updated
        foreach ($requestPayload['movements_details'] as $detail) {
            $this->assertDatabaseHas('product_batches', [
                'product_id' => $detail['product_id'],
                'price' => $detail['price'],
                'expiration_date' => $detail['expiration_date'],
                'quantity' => $detail['count']
            ]);
        }
    }

    /** @test */
    public function it_handles_unique_constraint_violations()
    {

        $voucherData = [
            'series' => 'F002',
            'number' => '54321',
            'amount' => 2000,
            'status' => 'pagado',
            'issue_date' => now()->format('Y-m-d'),
        ];

        $requestPayload = [
            'voucher' => $voucherData,
            'user_id' => 1,
            'comment' => 'Duplicate batch test',
            'category_movements_id' => 1,
            'provider_id' => 1,
            'warehouse_id' => 1,
            'movements_details' => [
                [
                    'product_id' => 1,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 10.00,
                    'count' => 10
                ],
                [
                    'product_id' => 3,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 14.00,
                    'count' => 10
                ]
            ]
        ];



        $response = $this->postJson('/api/stock-movements/store-entry', $requestPayload);

        //$response->assertStatus(201);


        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'Entry created successfully.'
            ]);

        // Verificar que el lote de producto ha sido actualizado
        $this->assertDatabaseHas('product_batches', [
            'product_id' => 1,
            'price' => 10.00,
            'expiration_date' => now()->addYear()->format('Y-m-d'),
            'quantity' => 15 // La cantidad debería estar actualizada
        ]);

        $this->assertDatabaseHas('product_batches', [
            'product_id' => 3,
            'price' => 14.00,
            'expiration_date' => now()->addYear()->format('Y-m-d'),
            'quantity' => 10 // Nuevo lote, cantidad debe ser la enviada
        ]);
    }

    /** @test */
    public function it_rollback_transaction_on_failure()
    {
        $this->expectException(\Exception::class);

        $voucherData = [
            'series' => 'F003',
            'number' => '99999',
            'amount' => 3000,
            'status' => 'pagado',
            'issue_date' => now()->format('Y-m-d H:i:s'),
        ];

        $requestPayload = [
            'voucher' => $voucherData,
            'user_id' => 1,
            'comment' => 'Testing rollback on failure',
            'category_movements_id' => 1,
            'provider_id' => 1,
            'warehouse_id' => 1,
            'movements_details' => [
                [
                    'product_id' => 5,
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 10.00,
                    'count' => 5
                ],
                // Forzamos un error con un valor inválido
                [
                    'product_id' => null, // Esto debe causar un error de validación.
                    'expiration_date' => now()->addYear()->format('Y-m-d'),
                    'price' => 20.00,
                    'count' => 5
                ]
            ]
        ];


        $this->postJson('/api/stock-movements/store-entry', $requestPayload);

        // Verifica que no se haya guardado ningún registro
        $this->assertDatabaseMissing('vouchers', ['series' => 'F003']);
        $this->assertDatabaseMissing('stock_movements', ['comment' => 'Testing rollback on failure']);
        $this->assertDatabaseMissing('product_batches', ['product_id' => 5, 'price' => 10.00]);
        $this->assertDatabaseMissing('movement_details', ['product_id' => 5]);
    }
}
