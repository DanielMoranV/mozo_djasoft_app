<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
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
            'number' => $this->number,
            'company_id' => $this->company_id,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'status' => $this->status,
            'expected_delivery' => $this->expected_delivery,
            'amount' => $this->amount,
            'provider_id' => $this->provider_id,
            'provider' => new ProviderResource($this->whenLoaded('provider')),
            'warehouse_id' => $this->warehouse_id,
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'purchaseOrderDetail' => PurchaseOrderDetailResource::collection($this->whenLoaded('purchaseOrderDetail')),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}