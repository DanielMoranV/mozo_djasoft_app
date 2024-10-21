<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
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
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'category_movements_id' => $this->category_movements_id,
            'provider_id' => $this->provider_id,
            'voucher_id' => $this->voucher_id,
            'created_at' => $this->created_at,
            'category_movement' => new CategoryMovementResource($this->whenLoaded('categoryMovement')),
            'voucher' => new VoucherResource($this->whenLoaded('voucher')),
            'provider' => new ProviderResource($this->whenLoaded('provider')),
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
            'movements_details' => MovementsDetailResource::collection($this->whenLoaded('movementDetails')),

        ];
    }
}