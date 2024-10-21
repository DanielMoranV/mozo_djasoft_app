<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subTotal = $this->price && $this->quantity ? $this->quantity * $this->price : 0;

        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'expiration_date' => $this->expiration_date,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sub_total' => $subTotal
        ];
    }
}