<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementsDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Verifica si la relación productBatch está cargada para evitar errores
        $productBatch = $this->whenLoaded('productBatch');

        // Calcula el total multiplicando count por product_batch.price, si está disponible
        $total = $productBatch ? $this->count * $productBatch->price : 0;

        return [
            'id' => $this->id,
            'product_batch_id' => $this->product_batch_id,
            'stock_movement_id' => $this->stock_movement_id,
            'count' => $this->count,
            'created_at' => $this->created_at,
            'product_batch' => new ProductBatchResource($this->whenLoaded('productBatch')),
            'sub_total' => $total
        ];
    }
}