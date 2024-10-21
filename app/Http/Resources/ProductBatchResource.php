<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
            'batch_number' => $this->batch_number,
            'expiration_date' => $this->expiration_date,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}